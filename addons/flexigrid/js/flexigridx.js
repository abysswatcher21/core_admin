/*
 * Flexigrid for jQuery -  v1.1
 *
 * Copyright (c) 2008 Paulo P. Marinas (code.google.com/p/flexigrid/)
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * http://jquery.org/license
 *
 */
(function ($) {

    jQuery.fn.center = function () {
        this.css("position", "absolute");
        this.css("top", Math.max(0, (($(window).height() - $(this).outerHeight()) / 2) +
                $(window).scrollTop()) + "px");
        this.css("left", Math.max(0, (($(window).width() - $(this).outerWidth()) / 2) +
                $(window).scrollLeft()) + "px");
        return this;
    }

    /*
     * jQuery 1.9 support. browser object has been removed in 1.9
     */
    var browser = $.browser

    if (!browser) {
        function uaMatch(ua) {
            ua = ua.toLowerCase();

            var match = /(chrome)[ \/]([\w.]+)/.exec(ua) ||
                    /(webkit)[ \/]([\w.]+)/.exec(ua) ||
                    /(opera)(?:.*version|)[ \/]([\w.]+)/.exec(ua) ||
                    /(msie) ([\w.]+)/.exec(ua) ||
                    ua.indexOf("compatible") < 0 && /(mozilla)(?:.*? rv:([\w.]+)|)/.exec(ua) ||
                    [];

            return {
                browser: match[ 1 ] || "",
                version: match[ 2 ] || "0"
            };
        }
        ;

        var matched = uaMatch(navigator.userAgent);
        browser = {};

        if (matched.browser) {
            browser[ matched.browser ] = true;
            browser.version = matched.version;
        }

        // Chrome is Webkit, but Webkit is also Safari.
        if (browser.chrome) {
            browser.webkit = true;
        } else if (browser.webkit) {
            browser.safari = true;
        }
    }

    /*!
     * START code from jQuery UI
     *
     * Copyright 2011, AUTHORS.txt (http://jqueryui.com/about)
     * Dual licensed under the MIT or GPL Version 2 licenses.
     * http://jquery.org/license
     *
     * http://docs.jquery.com/UI
     */

    if (typeof $.support.selectstart != 'function') {
        $.support.selectstart = "onselectstart" in document.createElement("div");
    }

    if (typeof $.fn.disableSelection != 'function') {
        $.fn.disableSelection = function () {
            return this.bind(($.support.selectstart ? "selectstart" : "mousedown") +
                    ".ui-disableSelection", function (event) {
                        event.preventDefault();
                    });
        };
    }

    /* END code from jQuery UI */

    $.addFlex = function (t, p) {
        if (t.grid)
            return false; //return if already exist
        p = $.extend({//apply default properties
            grid_id: 'grid_' + t.id,
            height: 200, //default height
            width: 'auto', //auto width
            striped: true, //apply odd even stripes
            novstripe: false,
            minwidth: 30, //min width of columns
            minheight: 80, //min height of columns
            resizable: false, //allow table resizing
            url: false, //URL if using data from AJAX
            method: 'POST', //data sending method
            dataType: 'xml', //type of data for AJAX, either xml or json
             errormsg: 'Connection Error', 
            // errormsg: 'Koneksi gagal dilakukan',
            usepager: false,
            nowrap: true,
            page: 1, //current page
            total: 1, //total pages
            useRp: true, //use the results per page select box
            rp: 15, //results per page
            rpOptions: [10, 30, 50, 100], //allowed per-page values
            title: false,
            title_class: false,
            idProperty: 'id',
            pagestat: 'Displaying {from} to {to} of {total} items', 
            // pagestat: 'Menampilkan {from} - {to} data dari total {total} data',
             pagetext: 'Page',
             outof: 'of',
             findtext: 'Find', 
            // pagetext: 'Halaman',
            // outof: 'dari',
            // findtext: 'Pencarian',
            findbuttontext: 'Search',
            params: [], //allow optional parameters to be passed around
            procmsg: 'Processing, please wait ...',
            // procmsg: 'Sedang diproses, harap tunggu ...',
            query: '',
            option: '',
            optionused: false,
            date_start: '',
            date_end: '',
            num_start: '',
            num_end: '',
            soption: '',
            sdatestart: '',
            sdateend: '',
            snumstart: '',
            snumend: '',
            qtype: '',
            nomsg: 'No items',
            // nomsg: 'Tidak ada data',
            minColToggle: 1, //minimum allowed column to be hidden
            showToggleBtn: true, //show or hide column toggle popup
            hideOnSubmit: true,
            autoload: true,
            blockOpacity: 0.5,
            preProcess: false,
            addTitleToCell: false, // add a title attr to cells with truncated contents
            dblClickResize: false, //auto resize column by double clicking
            onDragCol: false,
            onToggleCol: false,
            onChangeSort: false,
            onDoubleClick: false,
            onSuccess: false,
            onError: false,
            onSubmit: false, //using a custom populate function
            __mw: {//extendable middleware function holding object
                datacol: function (p, col, val) { //middleware for formatting data columns
                    var _col = (typeof p.datacol[col] == 'function') ? p.datacol[col](val) : val; //format column using function
                    if (typeof p.datacol['*'] == 'function') { //if wildcard function exists
                        return p.datacol['*'](_col); //run wildcard function
                    } else {
                        return _col; //return column without wildcard
                    }
                }
            },
            getGridClass: function (g) { //get the grid class, always returns g
                return g;
            },
            datacol: {}, //datacol middleware object 'colkey': function(colval) {}
            colResize: true, //from: http://stackoverflow.com/a/10615589
            colMove: true
        }, p);
        $(t).show() //show if hidden
                .attr({
                    cellPadding: 0,
                    cellSpacing: 0,
                    border: 0
                }) //remove padding and spacing
                .removeAttr('width'); //remove width properties
        //create grid class
        var g = {
            hset: {},
            rePosDrag: function () {
                var cdleft = 0 - this.hDiv.scrollLeft;
                if (this.hDiv.scrollLeft > 0)
                    cdleft -= Math.floor(p.cgwidth / 2);
                $(g.cDrag).css({
                    top: g.hDiv.offsetTop + 1
                });
                var cdpad = this.cdpad;
                var cdcounter = 0;
                $('div', g.cDrag).hide();
                $('thead tr:first th:visible', this.hDiv).each(function () {
                    var n = $('thead tr:first th:visible', g.hDiv).index(this);
                    var cdpos = parseInt($('div', this).width());
                    if (cdleft == 0)
                        cdleft -= Math.floor(p.cgwidth / 2);
                    cdpos = cdpos + cdleft + cdpad;
                    if (isNaN(cdpos)) {
                        cdpos = 0;
                    }
                    $('div:eq(' + n + ')', g.cDrag).css({
                        'left': (!(browser.mozilla) ? cdpos - cdcounter : cdpos) + 'px'
                    }).show();
                    cdleft = cdpos;
                    cdcounter++;
                });
            },
            fixHeight: function (newH) {
                newH = false;
                if (!newH)
                    newH = $(g.bDiv).height();
                var hdHeight = $(this.hDiv).height();
                $('div', this.cDrag).each(
                        function () {
                            $(this).height(newH + hdHeight);
                        }
                );
                var nd = parseInt($(g.nDiv).height(), 10);
                if (nd > newH)
                    $(g.nDiv).height(newH).width(200);
                else
                    $(g.nDiv).height('auto').width('auto');
                $(g.block).css({
                    height: newH,
                    marginBottom: (newH * -1)
                });
                var hrH = g.bDiv.offsetTop + newH;
                if (p.height != 'auto' && p.resizable)
                    hrH = g.vDiv.offsetTop;
                $(g.rDiv).css({
                    height: hrH
                });
            },
            dragStart: function (dragtype, e, obj) { //default drag function start
                if (dragtype == 'colresize' && p.colResize === true) {//column resize
                    $(g.nDiv).hide();
                    $(g.nBtn).hide();
                    var n = $('div', this.cDrag).index(obj);
                    var ow = $('th:visible div:eq(' + n + ')', this.hDiv).width();
                    $(obj).addClass('dragging').siblings().hide();
                    $(obj).prev().addClass('dragging').show();
                    this.colresize = {
                        startX: e.pageX,
                        ol: parseInt(obj.style.left, 10),
                        ow: ow,
                        n: n
                    };
                    $('body').css('cursor', 'col-resize');
                } else if (dragtype == 'vresize') {//table resize
                    var hgo = false;
                    $('body').css('cursor', 'row-resize');
                    if (obj) {
                        hgo = true;
                        $('body').css('cursor', 'col-resize');
                    }
                    this.vresize = {
                        h: p.height,
                        sy: e.pageY,
                        w: p.width,
                        sx: e.pageX,
                        hgo: hgo
                    };
                } else if (dragtype == 'colMove') {//column header drag
                    $(e.target).disableSelection(); //disable selecting the column header
                    if ((p.colMove === true)) {
                        $(g.nDiv).hide();
                        $(g.nBtn).hide();
                        this.hset = $(this.hDiv).offset();
                        this.hset.right = this.hset.left + $('table', this.hDiv).width();
                        this.hset.bottom = this.hset.top + $('table', this.hDiv).height();
                        this.dcol = obj;
                        this.dcoln = $('th', this.hDiv).index(obj);
                        this.colCopy = document.createElement("div");
                        this.colCopy.className = "colCopy";
                        this.colCopy.innerHTML = obj.innerHTML;
                        if (browser.msie) {
                            this.colCopy.className = "colCopy ie";
                        }
                        $(this.colCopy).css({
                            position: 'absolute',
                            'float': 'left',
                            display: 'none',
                            textAlign: obj.align
                        });
                        $('body').append(this.colCopy);
                        $(this.cDrag).hide();
                    }
                }
                $('body').noSelect();
            },
            dragMove: function (e) {
                if (this.colresize) {//column resize
                    var n = this.colresize.n;
                    var diff = e.pageX - this.colresize.startX;
                    var nleft = this.colresize.ol + diff;
                    var nw = this.colresize.ow + diff;
                    if (nw > p.minwidth) {
                        $('div:eq(' + n + ')', this.cDrag).css('left', nleft);
                        this.colresize.nw = nw;
                    }
                } else if (this.vresize) {//table resize
                    var v = this.vresize;
                    var y = e.pageY;
                    var diff = y - v.sy;
                    if (!p.defwidth)
                        p.defwidth = p.width;
                    if (p.width != 'auto' && !p.nohresize && v.hgo) {
                        var x = e.pageX;
                        var xdiff = x - v.sx;
                        var newW = v.w + xdiff;
                        if (newW > p.defwidth) {
                            this.gDiv.style.width = newW + 'px';
                            p.width = newW;
                        }
                    }
                    var newH = v.h + diff;
                    if ((newH > p.minheight || p.height < p.minheight) && !v.hgo) {
                        this.bDiv.style.height = newH + 'px';
                        p.height = newH;
                        this.fixHeight(newH);
                    }
                    v = null;
                } else if (this.colCopy) {
                    $(this.dcol).addClass('thMove').removeClass('thOver');
                    if (e.pageX > this.hset.right || e.pageX < this.hset.left || e.pageY > this.hset.bottom || e.pageY < this.hset.top) {
                        //this.dragEnd();
                        $('body').css('cursor', 'move');
                    } else {
                        $('body').css('cursor', 'pointer');
                    }
                    $(this.colCopy).css({
                        top: e.pageY + 10,
                        left: e.pageX + 20,
                        display: 'block'
                    });
                }
            },
            dragEnd: function () {
                if (this.colresize) {
                    var n = this.colresize.n;
                    var nw = this.colresize.nw;
                    $('th:visible div:eq(' + n + ')', this.hDiv).css('width', nw);
                    $('tr', this.bDiv).each(
                            function () {
                                var $tdDiv = $('td:visible div:eq(' + n + ')', this);
                                $tdDiv.css('width', nw);
                                g.addTitleToCell($tdDiv);
                            }
                    );
                    this.hDiv.scrollLeft = this.bDiv.scrollLeft;
                    $('div:eq(' + n + ')', this.cDrag).siblings().show();
                    $('.dragging', this.cDrag).removeClass('dragging');
                    this.rePosDrag();
                    this.fixHeight();
                    this.colresize = false;
                    if ($.cookies) {
                        var name = p.colModel[n].name;		// Store the widths in the cookies
                        $.cookie('flexiwidths/' + name, nw);
                    }
                } else if (this.vresize) {
                    this.vresize = false;
                } else if (this.colCopy) {
                    $(this.colCopy).remove();
                    if (this.dcolt !== null) {
                        if (this.dcoln > this.dcolt)
                            $('th:eq(' + this.dcolt + ')', this.hDiv).before(this.dcol);
                        else
                            $('th:eq(' + this.dcolt + ')', this.hDiv).after(this.dcol);
                        this.switchCol(this.dcoln, this.dcolt);
                        $(this.cdropleft).remove();
                        $(this.cdropright).remove();
                        this.rePosDrag();
                        if (p.onDragCol) {
                            p.onDragCol(this.dcoln, this.dcolt);
                        }
                    }
                    this.dcol = null;
                    this.hset = null;
                    this.dcoln = null;
                    this.dcolt = null;
                    this.colCopy = null;
                    $('.thMove', this.hDiv).removeClass('thMove');
                    $(this.cDrag).show();
                }
                $('body').css('cursor', 'default');
                $('body').noSelect(false);
            },
            toggleCol: function (cid, visible) {
                var ncol = $("th[axis='col" + cid + "']", this.hDiv)[0];
                var n = $('thead th', g.hDiv).index(ncol);
                var cb = $('input[value=' + cid + ']', g.nDiv)[0];
                if (visible == null) {
                    visible = ncol.hidden;
                }
                if ($('input:checked', g.nDiv).length < p.minColToggle && !visible) {
                    return false;
                }
                if (visible) {
                    ncol.hidden = false;
                    $(ncol).show();
                    cb.checked = true;
                } else {
                    ncol.hidden = true;
                    $(ncol).hide();
                    cb.checked = false;
                }
                $('tbody tr', t).each(
                        function () {
                            if (visible) {
                                $('td:eq(' + n + ')', this).show();
                            } else {
                                $('td:eq(' + n + ')', this).hide();
                            }
                        }
                );
                this.rePosDrag();
                if (p.onToggleCol) {
                    p.onToggleCol(cid, visible);
                }
                return visible;
            },
            switchCol: function (cdrag, cdrop) { //switch columns
                $('tbody tr', t).each(
                        function () {
                            if (cdrag > cdrop)
                                $('td:eq(' + cdrop + ')', this).before($('td:eq(' + cdrag + ')', this));
                            else
                                $('td:eq(' + cdrop + ')', this).after($('td:eq(' + cdrag + ')', this));
                        }
                );
                //switch order in nDiv
                if (cdrag > cdrop) {
                    $('tr:eq(' + cdrop + ')', this.nDiv).before($('tr:eq(' + cdrag + ')', this.nDiv));
                } else {
                    $('tr:eq(' + cdrop + ')', this.nDiv).after($('tr:eq(' + cdrag + ')', this.nDiv));
                }
                if (browser.msie && browser.version < 7.0) {
                    $('tr:eq(' + cdrop + ') input', this.nDiv)[0].checked = true;
                }
                this.hDiv.scrollLeft = this.bDiv.scrollLeft;
            },
            scroll: function () {
                this.hDiv.scrollLeft = this.bDiv.scrollLeft;
                this.rePosDrag();
            },
            addData: function (data) { //parse data
                if (p.dataType == 'json') {
                    data = $.extend({
                        rows: [],
                        page: 0,
                        total: 0
                    }, data);
                }
                if (p.preProcess) {
                    data = p.preProcess(data);
                }
                $('.pReload', this.pDiv).removeClass('loading');
                this.loading = false;
                if (!data) {
                    $('.pPageStat', this.pDiv).html(p.errormsg);
                    if (p.onSuccess)
                        p.onSuccess(this);
                    return false;
                }
                if (p.dataType == 'xml') {
                    p.total = +$('rows total', data).text();
                } else {
                    p.total = data.total;
                }
                if (p.total == 0) {
                    $('tr, a, td, div', t).unbind();
                    $(t).empty();
                    p.pages = 1;
                    p.page = 1;
                    this.buildpager();
                    $('.pPageStat', this.pDiv).html(p.nomsg);
                    if (p.onSuccess)
                        p.onSuccess(this);
                    return false;
                }
                p.pages = Math.ceil(p.total / p.rp);
                if (p.dataType == 'xml') {
                    p.page = +$('rows page', data).text();
                } else {
                    p.page = data.page;
                }
                this.buildpager();
                //build new body
                var tbody = document.createElement('tbody');
                if (p.dataType == 'json') {
                    $.each(data.rows, function (i, row) {
                        var tr = document.createElement('tr');
                        var jtr = $(tr);
                        if (row.name)
                            tr.name = row.name;
                        if (row.color) {
                            jtr.css('background', row.color);
                        } else {
                            if (i % 2 && p.striped)
                                tr.className = 'erow';
                        }
                        if (row[p.idProperty]) {
                            /* ADDITIONAL STARTS HERE / AWAL TAMBAHAN */
                            //tr.id = 'row' + row[p.idProperty];
                            tr.id = t.id + '_row_' + row[p.idProperty];
                            /* ADDITIONAL ENDS HERE / AKHIR TAMBAHAN */
                            jtr.attr('data-id', row[p.idProperty]);
                        }
                        /* Ini tambahan */
                        if (row['additionalClass']) {
                            jtr.addClass(row['additionalClass']);
                        }
                        /* akhir tambahan */
                        $('thead tr:first th', g.hDiv).each(//add cell
                                function () {
                                    var td = document.createElement('td');
                                    var idx = $(this).attr('axis').substr(3);
                                    td.align = this.align;
                                    // If each row is the object itself (no 'cell' key)
                                    if (typeof row.cell == 'undefined') {
                                        td.innerHTML = row[p.colModel[idx].name];
                                    } else {
                                        // If the json elements aren't named (which is typical), use numeric order
                                        var iHTML = '';
                                        if (typeof row.cell[idx] != "undefined") {
                                            iHTML = (row.cell[idx] !== null) ? row.cell[idx] : ''; //null-check for Opera-browser
                                        } else {
                                            iHTML = row.cell[p.colModel[idx].name];
                                        }
                                        td.innerHTML = p.__mw.datacol(p, $(this).attr('abbr'), iHTML); //use middleware datacol to format cols
                                    }
                                    // If the content has a <BGCOLOR=nnnnnn> option, decode it.
                                    var offs = td.innerHTML.indexOf('<BGCOLOR=');
                                    if (offs > 0) {
                                        $(td).css('background', text.substr(offs + 7, 7));
                                    }

                                    $(td).attr('abbr', $(this).attr('abbr'));
                                    $(tr).append(td);
                                    td = null;
                                }
                        );
                        if ($('thead', this.gDiv).length < 1) {//handle if grid has no headers
                            for (idx = 0; idx < row.cell.length; idx++) {
                                var td = document.createElement('td');
                                // If the json elements aren't named (which is typical), use numeric order
                                if (typeof row.cell[idx] != "undefined") {
                                    td.innerHTML = (row.cell[idx] != null) ? row.cell[idx] : '';//null-check for Opera-browser
                                } else {
                                    td.innerHTML = row.cell[p.colModel[idx].name];
                                }
                                $(tr).append(td);
                                td = null;
                            }
                        }
                        $(tbody).append(tr);
                        tr = null;
                    });
                } else if (p.dataType == 'xml') {
                    var i = 1;
                    $("rows row", data).each(function () {
                        i++;
                        var tr = document.createElement('tr');
                        if ($(this).attr('name'))
                            tr.name = $(this).attr('name');
                        if ($(this).attr('color')) {
                            $(tr).css('background', $(this).attr('id'));
                        } else {
                            if (i % 2 && p.striped)
                                tr.className = 'erow';
                        }
                        var nid = $(this).attr('id');
                        if (nid) {
                            tr.id = 'row' + nid;
                        }
                        nid = null;
                        var robj = this;
                        $('thead tr:first th', g.hDiv).each(function () {
                            var td = document.createElement('td');
                            var idx = $(this).attr('axis').substr(3);
                            td.align = this.align;

                            var text = $("cell:eq(" + idx + ")", robj).text();
                            var offs = text.indexOf('<BGCOLOR=');
                            if (offs > 0) {
                                $(td).css('background', text.substr(offs + 7, 7));
                            }
                            td.innerHTML = p.__mw.datacol(p, $(this).attr('abbr'), text); //use middleware datacol to format cols
                            $(td).attr('abbr', $(this).attr('abbr'));
                            $(tr).append(td);
                            td = null;
                        });
                        if ($('thead', this.gDiv).length < 1) {//handle if grid has no headers
                            $('cell', this).each(function () {
                                var td = document.createElement('td');
                                td.innerHTML = $(this).text();
                                $(tr).append(td);
                                td = null;
                            });
                        }
                        $(tbody).append(tr);
                        tr = null;
                        robj = null;
                    });
                }
                $('tr', t).unbind();
                $(t).empty();
                $(t).append(tbody);
                this.addCellProp();
                this.addRowProp();
                this.rePosDrag();
                tbody = null;
                data = null;
                i = null;
                if (p.onSuccess) {
                    p.onSuccess(this);
                }
                if (p.hideOnSubmit) {
                    $(g.block).remove();
                }
                this.hDiv.scrollLeft = this.bDiv.scrollLeft;
                if (browser.opera) {
                    $(t).css('visibility', 'visible');
                }
            },
            changeSort: function (th) { //change sortorder
                if (this.loading) {
                    return true;
                }
                $(g.nDiv).hide();
                $(g.nBtn).hide();
                if (p.sortname == $(th).attr('abbr')) {
                    if (p.sortorder == 'asc') {
                        p.sortorder = 'desc';
                    } else {
                        p.sortorder = 'asc';
                    }
                }
                $(th).addClass('sorted').siblings().removeClass('sorted');
                $('.sdesc', this.hDiv).removeClass('sdesc');
                $('.sasc', this.hDiv).removeClass('sasc');
                $('div', th).addClass('s' + p.sortorder);
                p.sortname = $(th).attr('abbr');
                if (p.onChangeSort) {
                    p.onChangeSort(p.sortname, p.sortorder);
                    this.populate();
                } else {
                    this.populate();
                }
                /* ADDITIONAL STARTS HERE / AWAL TAMBAHAN */
                $(g.gDiv).attr("data-sortname", p.sortname);
                $(g.gDiv).attr("data-sortorder", p.sortorder);
                /* ADDITIONAL ENDS HERE / AKHIR TAMBAHAN */
            },
            buildpager: function () { //rebuild pager based on new properties
                $('.pcontrol input', this.pDiv).val(p.page);
                $('.pcontrol span', this.pDiv).html(p.pages);
                var r1 = (p.page - 1) * p.rp + 1;
                var r2 = r1 + p.rp - 1;
                if (p.total < r2) {
                    r2 = p.total;
                }
                var stat = p.pagestat;
                stat = stat.replace(/{from}/, r1);
                stat = stat.replace(/{to}/, r2);
                stat = stat.replace(/{total}/, p.total);
                $('.pPageStat', this.pDiv).html(stat);
                $('#total_data', this.pDiv).html(p.total);
            },
            populate: function () { //get latest data
                if (this.loading) {
                    return true;
                }
                if (p.onSubmit) {
                    var gh = p.onSubmit();
                    if (!gh) {
                        return false;
                    }
                }
                this.loading = true;
                if (!p.url) {
                    return false;
                }
                $('.pPageStat', this.pDiv).html(p.procmsg);
                $('.pReload', this.pDiv).addClass('loading');
                $(g.block).css({
                    top: g.bDiv.offsetTop
                });
                if (p.hideOnSubmit) {
                    $(this.gDiv).prepend(g.block);
                }
                if (browser.opera) {
                    $(t).css('visibility', 'hidden');
                }
                if (!p.newp) {
                    p.newp = 1;
                }
                if (p.page > p.pages) {
                    p.page = p.pages;
                }
                var param = [{
                        name: 'page',
                        value: p.newp
                    }, {
                        name: 'rp',
                        value: p.rp
                    }, {
                        name: 'sortname',
                        value: p.sortname
                    }, {
                        name: 'sortorder',
                        value: p.sortorder
                    }, {
                        name: 'query',
                        value: p.query
                    }, {
                        name: 'querys',
                        value: p.querys
                    }, {
                        name: 'qtypes',
                        value: p.qtypes
                    }, {/* ADDITIONAL STARTS HERE / AWAL TAMBAHAN */
                        name: 'option',
                        value: p.option
                    }, {
                        name: 'optionused',
                        value: p.optionused
                    }, {
                        name: 'date_start',
                        value: p.date_start
                    }, {
                        name: 'date_end',
                        value: p.date_end
                    }, {
                        name: 'num_start',
                        value: p.num_start
                    }, {
                        name: 'num_end',
                        value: p.num_end
                    }, {/* ADDITIONAL ENDS HERE / AKHIR TAMBAHAN */
                        name: 'qtype',
                        value: p.qtype
                    }];
                if (p.params.length) {
                    for (var pi = 0; pi < p.params.length; pi++) {
                        param[param.length] = p.params[pi];
                    }
                }
                $.ajax({
                    type: p.method,
                    url: p.url,
                    data: param,
                    dataType: p.dataType,
                    success: function (data) {
                        g.addData(data);
                    },
                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                        try {
                            if (p.onError)
                                p.onError(XMLHttpRequest, textStatus, errorThrown);
                        } catch (e) {
                        }
                    }
                });
            },
            doSearch: function () {
                /* ADDITIONAL STARTS HERE / AWAL TAMBAHAN */
                var qselectused = false;
                var selectedoption = '';
                $(".qselect").each(function () {
                    var id = $(this).attr('id');
                    var show = $("#" + id).is(':hidden');
                    if (show == false) {
                        qselectused = true;
                        selectedoption = $("#" + id + " select[name=qoption] option:selected").val();
                    }
                });
                if (qselectused == true) {
                    p.option = selectedoption;
                    p.optionused = true;
                } else {
                    p.option = '';
                    p.optionused = false;
                }

                var querys = [];
                $('#'+ t.id + '_formModalFilter .querys').each(function () {
                    if ($(this).val() != '') {
                        if ($(this).hasClass('querys_text')) {
                            querys.push({
                                filter_type: 'querys_text',
                                filter_field: $(this).attr('param'),
                                filter_value: $(this).val()
                            });
                        } else if ($(this).hasClass('querys_num_start')) {
                            querys.push({
                                filter_type: 'querys_num_start',
                                filter_field: $(this).attr('param'),
                                filter_value: $(this).val()
                            });
                        } else if ($(this).hasClass('querys_num_end')) {
                            querys.push({
                                filter_type: 'querys_num_end',
                                filter_field: $(this).attr('param'),
                                filter_value: $(this).val()
                            });
                        } else if ($(this).hasClass('querys_option')) {
                            querys.push({
                                filter_type: 'querys_option',
                                filter_field: $(this).attr('param'),
                                filter_value: $(this).val()
                            });
                        } else if ($(this).hasClass('querys_date')) {
                            querys.push({
                                filter_type: 'querys_date',
                                filter_field: $(this).attr('param'),
                                filter_value: $(this).val()
                            });
                        }
                    }
                });
                p.querys = JSON.stringify(querys);
//                p.qtypes = $('.search_popup input[name=querys]').attr('title');
                p.query = $('#'+ t.id + '_formModalFilter input[name=q]', g.sDiv).val();
                p.date_start = $('#'+ t.id + '_formModalFilter input[name=qdatestart]', g.sDiv).val();
                p.date_end = $('#'+ t.id + '_formModalFilter input[name=qdateend]', g.sDiv).val();
                p.num_start = $('#'+ t.id + '_formModalFilter input[name=qnumstart]', g.sDiv).val();
                p.num_end = $('#'+ t.id + '_formModalFilter input[name=qnumend]', g.sDiv).val();
                p.qtype = $('#'+ t.id + '_formModalFilter select[name=qtype]', g.sDiv).val();
                p.newp = 1;
                this.populate();
                /* ADDITIONAL ENDS HERE / AKHIR TAMBAHAN */
            },
            doClear: function () {
                $("#" + t.id + "_formModalFilter input").val('');
                $("#" + t.id + "_formModalFilter select").val('').change();
                $('#' + t.id + '_formModalFilter').modal('hide');
                p.option = '';
                p.optionused = false;
                p.query = '';
                p.date_start = '';
                p.date_end = '';
                p.num_start = '';
                p.num_end = '';
                p.qtype = '';
                p.newp = 1;
                var self = this;
                setTimeout(function () {
                    self.doSearch();
                }, 100);
            },
            changePage: function (ctype) { //change page
                if (this.loading) {
                    return true;
                }
                switch (ctype) {
                    case 'first':
                        p.newp = 1;
                        break;
                    case 'prev':
                        if (p.page > 1) {
                            p.newp = parseInt(p.page, 10) - 1;
                        }
                        break;
                    case 'next':
                        if (p.page < p.pages) {
                            p.newp = parseInt(p.page, 10) + 1;
                        }
                        break;
                    case 'last':
                        p.newp = p.pages;
                        break;
                    case 'input':
                        var nv = parseInt($('.pcontrol input', this.pDiv).val(), 10);
                        if (isNaN(nv)) {
                            nv = 1;
                        }
                        if (nv < 1) {
                            nv = 1;
                        } else if (nv > p.pages) {
                            nv = p.pages;
                        }
                        $('.pcontrol input', this.pDiv).val(nv);
                        p.newp = nv;
                        break;
                }
                if (p.newp == p.page) {
                    return false;
                }
                if (p.onChangePage) {
                    p.onChangePage(p.newp);
                } else {
                    this.populate();
                }
            },
            addCellProp: function () {
                $('tbody tr td', g.bDiv).each(function () {
                    var tdDiv = document.createElement('div');
                    var n = $('td', $(this).parent()).index(this);
                    var pth = $('th:eq(' + n + ')', g.hDiv).get(0);
                    if (pth != null) {
                        if (p.sortname == $(pth).attr('abbr') && p.sortname) {
                            this.className = 'sorted';
                        }
                        $(tdDiv).css({
                            textAlign: pth.align,
                            width: $('div:first', pth)[0].style.width
                        });
                        if (pth.hidden) {
                            $(this).css('display', 'none');
                        }
                    }
                    if (p.nowrap == false) {
                        $(tdDiv).css('white-space', 'normal');
                    }
                    /* ADDITIONAL STARTS HERE / AWAL TAMBAHAN */
                    /*
                     if (this.innerHTML == '') {
                     this.innerHTML = '&nbsp;';
                     }
                     */
                    var str_prnt_id = $(this).parent()[0].id;
                    var prnt_id = str_prnt_id.substring(str_prnt_id.lastIndexOf("_") + 1, str_prnt_id.length);
                    if (!$(this).children().is('span')) {
                        //tdDiv.innerHTML = '<span id="' + $(pth).attr('abbr') + '_' + prnt_id + '">' + this.innerHTML + '</span>';
                        tdDiv.innerHTML = '<span>' + this.innerHTML + '</span>';
                    } else {
                        tdDiv.innerHTML = this.innerHTML;
                    }
                    //tdDiv.innerHTML = this.innerHTML;
                    var prnt = $(this).parent()[0];
                    var pid = false;
                    if (prnt.id) {
                        pid = prnt.id.substr(3);
                    }
                    if (pth != null) {
                        if (pth.process)
                            pth.process(tdDiv, pid);
                    }
                    $(this).empty().append(tdDiv).removeAttr('width'); //wrap content
                    g.addTitleToCell(tdDiv);
                });
            },
            getCellDim: function (obj) {// get cell prop for editable event
                var ht = parseInt($(obj).height(), 10);
                var pht = parseInt($(obj).parent().height(), 10);
                var wt = parseInt(obj.style.width, 10);
                var pwt = parseInt($(obj).parent().width(), 10);
                var top = obj.offsetParent.offsetTop;
                var left = obj.offsetParent.offsetLeft;
                var pdl = parseInt($(obj).css('paddingLeft'), 10);
                var pdt = parseInt($(obj).css('paddingTop'), 10);
                return {
                    ht: ht,
                    wt: wt,
                    top: top,
                    left: left,
                    pdl: pdl,
                    pdt: pdt,
                    pht: pht,
                    pwt: pwt
                };
            },
            addRowProp: function () {
                $('tbody tr', g.bDiv).on('click', function (e) {
                    var obj = (e.target || e.srcElement);
                    if (obj.href || obj.type)
                        return true;
                    if (e.ctrlKey || e.metaKey) {
                        // mousedown already took care of this case
                        return;
                    }
                    $(this).toggleClass('trSelected');
                    if (p.singleSelect && !g.multisel) {
                        $(this).siblings().removeClass('trSelected');
                    }
                }).on('mousedown', function (e) {
                    if (e.shiftKey) {
                        $(this).toggleClass('trSelected');
                        g.multisel = true;
                        this.focus();
                        $(g.gDiv).noSelect();
                    }
                    if (e.ctrlKey || e.metaKey) {
                        $(this).toggleClass('trSelected');
                        g.multisel = true;
                        this.focus();
                    }
                }).on('mouseup', function (e) {
                    if (g.multisel && !(e.ctrlKey || e.metaKey)) {
                        g.multisel = false;
                        $(g.gDiv).noSelect(false);
                    }
                }).on('dblclick', function () {
                    if (p.onDoubleClick) {
                        p.onDoubleClick(this, g, p);
                    }
                }).hover(function (e) {
                    if (g.multisel && e.shiftKey) {
                        $(this).toggleClass('trSelected');
                    }
                }, function () {});
                if (browser.msie && browser.version < 7.0) {
                    $(this).hover(function () {
                        $(this).addClass('trOver');
                    }, function () {
                        $(this).removeClass('trOver');
                    });
                }
            },

            combo_flag: true,
            combo_resetIndex: function (selObj)
            {
                if (this.combo_flag) {
                    selObj.selectedIndex = 0;
                }
                this.combo_flag = true;
            },
            combo_doSelectAction: function (selObj)
            {
                eval(selObj.options[selObj.selectedIndex].value);
                selObj.selectedIndex = 0;
                this.combo_flag = false;
            },
            //Add title attribute to div if cell contents is truncated
            addTitleToCell: function (tdDiv) {
                if (p.addTitleToCell) {
                    var $span = $('<span />').css('display', 'none'),
                            $div = (tdDiv instanceof jQuery) ? tdDiv : $(tdDiv),
                            div_w = $div.outerWidth(),
                            span_w = 0;

                    $('body').children(':first').before($span);
                    $span.html($div.html());
                    $span.css('font-size', '' + $div.css('font-size'));
                    $span.css('padding-left', '' + $div.css('padding-left'));
                    span_w = $span.innerWidth();
                    $span.remove();

                    if (span_w > div_w) {
                        $div.attr('param', $div.text());
                    } else {
                        $div.removeAttr('param');
                    }
                }
            },
            autoResizeColumn: function (obj) {
                if (!p.dblClickResize) {
                    return;
                }
                var n = $('div', this.cDrag).index(obj),
                        $th = $('th:visible div:eq(' + n + ')', this.hDiv),
                        ol = parseInt(obj.style.left, 10),
                        ow = $th.width(),
                        nw = 0,
                        nl = 0,
                        $span = $('<span />');
                $('body').children(':first').before($span);
                $span.html($th.html());
                $span.css('font-size', '' + $th.css('font-size'));
                $span.css('padding-left', '' + $th.css('padding-left'));
                $span.css('padding-right', '' + $th.css('padding-right'));
                nw = $span.width();
                $('tr', this.bDiv).each(function () {
                    var $tdDiv = $('td:visible div:eq(' + n + ')', this),
                            spanW = 0;
                    $span.html($tdDiv.html());
                    $span.css('font-size', '' + $tdDiv.css('font-size'));
                    $span.css('padding-left', '' + $tdDiv.css('padding-left'));
                    $span.css('padding-right', '' + $tdDiv.css('padding-right'));
                    spanW = $span.width();
                    nw = (spanW > nw) ? spanW : nw;
                });
                $span.remove();
                nw = (p.minWidth > nw) ? p.minWidth : nw;
                nl = ol + (nw - ow);
                $('div:eq(' + n + ')', this.cDrag).css('left', nl);
                this.colresize = {
                    nw: nw,
                    n: n
                };
                g.dragEnd();
            },
            pager: 0
        };

        g = p.getGridClass(g); //get the grid class

        if (p.colModel) { //create model if any
            thead = document.createElement('thead');
            var tr = document.createElement('tr');
            for (var i = 0; i < p.colModel.length; i++) {
                var cm = p.colModel[i];
                var th = document.createElement('th');
                $(th).attr('axis', 'col' + i);
                if (cm) {	// only use cm if its defined
                    if ($.cookies) {
                        var cookie_width = 'flexiwidths/' + cm.name;		// Re-Store the widths in the cookies
                        if ($.cookie(cookie_width) != undefined) {
                            cm.width = $.cookie(cookie_width);
                        }
                    }
                    if (cm.display != undefined) {
                        th.innerHTML = cm.display;
                    }
                    //if (cm.name && cm.sortable) {
                    if (cm.name && cm.sortable) {
                        $(th).attr('abbr', cm.name);
                    }
                    /* ADDITIONAL STARTS HERE / AWAL TAMBAHAN */
                    if (cm.datasource == false) {
                        $(th).addClass('no_datasource');
                    }
                    /* ADDITIONAL ENDS HERE / AKHIR TAMBAHAN */
                    if (cm.align) {
                        th.align = cm.align;
                    }
                    if (cm.width) {
                        $(th).attr('width', cm.width);
                    }
                    if ($(cm).attr('hide')) {
                        th.hidden = true;
                    }
                    if (cm.process) {
                        th.process = cm.process;
                    }
                } else {
                    th.innerHTML = "";
                    $(th).attr('width', 30);
                }
                $(tr).append(th);
            }
            $(thead).append(tr);
            $(t).prepend(thead);
        } // end if p.colmodel
        //init divs
        g.gDiv = document.createElement('div'); //create global container
        g.mDiv = document.createElement('div'); //create title container
        g.hDiv = document.createElement('div'); //create header container
        g.bDiv = document.createElement('div'); //create body container
        g.vDiv = document.createElement('div'); //create grip
        g.rDiv = document.createElement('div'); //create horizontal resizer
        g.cDrag = document.createElement('div'); //create column drag
        g.block = document.createElement('div'); //creat blocker
        g.nDiv = document.createElement('div'); //create column show/hide popup
        g.nBtn = document.createElement('div'); //create column show/hide button
        g.iDiv = document.createElement('div'); //create editable layer
        g.tDiv = document.createElement('div'); //create toolbar
        g.sDiv = document.createElement('div');
        g.pDiv = document.createElement('div'); //create pager container

        if (p.colResize === false) { //don't display column drag if we are not using it
            $(g.cDrag).css('display', 'none');
        }

        if (!p.usepager) {
            g.pDiv.style.display = 'none';
        }
        g.hTable = document.createElement('table');
        g.gDiv.className = 'flexigrid';
        /* ADDITIONAL STARTS HERE / AWAL TAMBAHAN */
        g.gDiv.setAttribute("id", p.grid_id);
        g.gDiv.setAttribute("data-sortname", p.sortname);
        g.gDiv.setAttribute("data-sortorder", p.sortorder);
        /* ADDITIONAL ENDS HERE / AKHIR TAMBAHAN */
        if (p.width != 'auto') {
            g.gDiv.style.width = p.width + (isNaN(p.width) ? '' : 'px');
        }
        //add conditional classes
        if (browser.msie) {
            $(g.gDiv).addClass('ie');
        }
        if (p.novstripe) {
            $(g.gDiv).addClass('novstripe');
        }
        $(t).before(g.gDiv);
        $(g.gDiv).append(t);
        //set toolbar

        if (p.buttons_right) {
            g.tDiv.className = 'tDiv';
            var tDiv2 = document.createElement('div');
            tDiv2.className = 'tDiv2_right';
            for (var i = 0; i < p.buttons_right.length; i++) {
                var btn = p.buttons_right[i];
                if (!btn.separator) {
                    var btnDiv = document.createElement('div');
                    btnDiv.className = 'fbutton_right';
                    //btnDiv.innerHTML = ("<div><span>") + (btn.hidename ? "&nbsp;" : btn.name) + ("</span></div>");
                    btnDiv.innerHTML = "<div><span>" + (btn.display ? btn.display : (btn.hidename ? "&nbsp;" : btn.name)) + "</span></div>";
                    if (btn.bclass)
                        $('span', btnDiv).addClass(btn.bclass).css({
                            paddingLeft: 20
                        });
                    if (btn.bimage) // if bimage defined, use its string as an image url for this buttons right style (RS)
                        $('span', btnDiv).css('background', 'url(' + btn.bimage + ') no-repeat center left');
                    $('span', btnDiv).css('paddingLeft', 20);

                    if (btn.tooltip) // add title if exists (RS)
                        $('span', btnDiv)[0].title = btn.tooltip;

                    btnDiv.onpress = btn.onpress;
                    btnDiv.name = btn.name;
                    btnDiv.urlaction = btn.urlaction;
                    if (btn.id) {
                        btnDiv.id = btn.id;
                    }
                    if (btn.onpress) {
                        $(btnDiv).click(function () {
                            if (this.urlaction) {
                                this.onpress(this.id || this.name, g.gDiv, this.urlaction);
                            } else {
                                this.onpress(this.id || this.name, g.gDiv, '');
                            }
                        });
                    }
                    $(tDiv2).append(btnDiv);
                    if (browser.msie && browser.version < 7.0) {
                        $(btnDiv).hover(function () {
                            $(this).addClass('fbOver');
                        }, function () {
                            $(this).removeClass('fbOver');
                        });
                    }
                } else {
                    $(tDiv2).append("<div class='btnseparator_right'></div>");
                }
            }
            $(g.tDiv).append(tDiv2);
            $(g.gDiv).prepend(g.tDiv);
        }

        if (p.buttons) {
            g.tDiv.className = 'tDiv';
            var tDiv2 = document.createElement('div');
            tDiv2.className = 'tDiv2';
            for (var i = 0; i < p.buttons.length; i++) {
                var btn = p.buttons[i];
                if (!btn.separator) {
                    var btnDiv = document.createElement('div');
                    btnDiv.className = 'fbutton';
                    //btnDiv.innerHTML = ("<div><span>") + (btn.hidename ? "&nbsp;" : btn.name) + ("</span></div>");
                    btnDiv.innerHTML = "<div><span>" + (btn.display ? btn.display : (btn.hidename ? "&nbsp;" : btn.name)) + "</span></div>";
                    if (btn.bclass)
                        $('span', btnDiv).addClass(btn.bclass).css({
                            paddingLeft: 20
                        });
                    if (btn.bimage) // if bimage defined, use its string as an image url for this buttons style (RS)
                        $('span', btnDiv).css('background', 'url(' + btn.bimage + ') no-repeat center left');
                    $('span', btnDiv).css('paddingLeft', 20);

                    if (btn.tooltip) // add title if exists (RS)
                        $('span', btnDiv)[0].title = btn.tooltip;

                    btnDiv.onpress = btn.onpress;
                    btnDiv.name = btn.name;
                    btnDiv.urlaction = btn.urlaction;
                    if (btn.id) {
                        btnDiv.id = btn.id;
                    }
                    if (btn.onpress) {
                        $(btnDiv).click(function () {
                            if (this.urlaction) {
                                this.onpress(this.id || this.name, g.gDiv, this.urlaction);
                            } else {
                                this.onpress(this.id || this.name, g.gDiv, '');
                            }
                        });
                    }
                    $(tDiv2).append(btnDiv);
                    if (browser.msie && browser.version < 7.0) {
                        $(btnDiv).hover(function () {
                            $(this).addClass('fbOver');
                        }, function () {
                            $(this).removeClass('fbOver');
                        });
                    }
                } else {
                    $(tDiv2).append("<div class='btnseparator'></div>");
                }
            }
            $(g.tDiv).append(tDiv2);
            $(g.tDiv).append("<div style='clear:both'></div>");
            $(g.gDiv).prepend(g.tDiv);
        }
        g.hDiv.className = 'hDiv';

        // Define a combo button set with custom action'ed calls when clicked.
        if (p.combobuttons && $(g.tDiv2))
        {
            var btnDiv = document.createElement('div');
            btnDiv.className = 'fbutton';

            var tSelect = document.createElement('select');
            $(tSelect).change(function () {
                g.combo_doSelectAction(tSelect)
            });
            $(tSelect).click(function () {
                g.combo_resetIndex(tSelect)
            });
            tSelect.className = 'cselect';
            $(btnDiv).append(tSelect);

            for (i = 0; i < p.combobuttons.length; i++)
            {
                var btn = p.combobuttons[i];
                if (!btn.separator)
                {
                    var btnOpt = document.createElement('option');
                    btnOpt.innerHTML = btn.name;

                    if (btn.bclass)
                        $(btnOpt)
                                .addClass(btn.bclass)
                                .css({
                                    paddingLeft: 20
                                })
                                ;
                    if (btn.bimage)  // if bimage defined, use its string as an image url for this buttons style (RS)
                        $(btnOpt).css('background', 'url(' + btn.bimage + ') no-repeat center left');
                    $(btnOpt).css('paddingLeft', 20);

                    if (btn.tooltip) // add title if exists (RS)
                        $(btnOpt)[0].title = btn.tooltip;

                    if (btn.onpress)
                    {
                        btnOpt.value = btn.onpress;
                    }
                    $(tSelect).append(btnOpt);
                }
            }
            $('.tDiv2').append(btnDiv);
        }


        $(t).before(g.hDiv);
        g.hTable.cellPadding = 0;
        g.hTable.cellSpacing = 0;
        $(g.hDiv).append('<div class="hDivBox"></div>');
        $('div', g.hDiv).append(g.hTable);
        var thead = $("thead:first", t).get(0);
        if (thead)
            $(g.hTable).append(thead);
        thead = null;
        if (!p.colmodel)
            var ci = 0;
        $('thead tr:first th', g.hDiv).each(function () {
            var thdiv = document.createElement('div');
            if ($(this).attr('abbr')) {
                $(this).click(function (e) {
                    if (!$(this).hasClass('thOver'))
                        return false;
                    var obj = (e.target || e.srcElement);
                    if (obj.href || obj.type)
                        return true;
                    g.changeSort(this);
                });
                if ($(this).attr('abbr') == p.sortname) {
                    this.className = 'sorted';
                    thdiv.className = 's' + p.sortorder;
                }
            }
            if (this.hidden) {
                $(this).hide();
            }
            if (!p.colmodel) {
                $(this).attr('axis', 'col' + ci++);
            }

            // if there isn't a default width, then the column headers don't match
            // i'm sure there is a better way, but this at least stops it failing
            if (this.width == '') {
                this.width = 100;
            }

            $(thdiv).css({
                textAlign: this.align,
                width: this.width + 'px'
            });
            thdiv.innerHTML = this.innerHTML;
            $(this).empty().append(thdiv).removeAttr('width').mousedown(function (e) {
                g.dragStart('colMove', e, this);
            }).hover(function () {
                if (!g.colresize && !$(this).hasClass('thMove') && !g.colCopy) {
                    $(this).addClass('thOver');
                }
                if ($(this).attr('abbr') != p.sortname && !g.colCopy && !g.colresize && $(this).attr('abbr')) {
                    $('div', this).addClass('s' + p.sortorder);
                } else if ($(this).attr('abbr') == p.sortname && !g.colCopy && !g.colresize && $(this).attr('abbr')) {
                    var no = (p.sortorder == 'asc') ? 'desc' : 'asc';
                    $('div', this).removeClass('s' + p.sortorder).addClass('s' + no);
                }
                if (g.colCopy) {
                    var n = $('th', g.hDiv).index(this);
                    if (n == g.dcoln) {
                        return false;
                    }
                    if (n < g.dcoln) {
                        $(this).append(g.cdropleft);
                    } else {
                        $(this).append(g.cdropright);
                    }
                    g.dcolt = n;
                } else if (!g.colresize) {
                    var nv = $('th:visible', g.hDiv).index(this);
                    var onl = parseInt($('div:eq(' + nv + ')', g.cDrag).css('left'), 10);
                    var nw = jQuery(g.nBtn).outerWidth();
                    var nl = onl - nw + Math.floor(p.cgwidth / 2);
                    $(g.nDiv).hide();
                    $(g.nBtn).hide();
                    $(g.nBtn).css({
                        'left': nl,
                        top: g.hDiv.offsetTop
                    }).show();
                    var ndw = parseInt($(g.nDiv).width(), 10);
                    $(g.nDiv).css({
                        top: g.bDiv.offsetTop
                    });
                    if ((nl + ndw) > $(g.gDiv).width()) {
                        $(g.nDiv).css('left', onl - ndw + 1);
                    } else {
                        $(g.nDiv).css('left', nl);
                    }
                    if ($(this).hasClass('sorted')) {
                        $(g.nBtn).addClass('srtd');
                    } else {
                        $(g.nBtn).removeClass('srtd');
                    }
                }
            }, function () {
                $(this).removeClass('thOver');
                if ($(this).attr('abbr') != p.sortname) {
                    $('div', this).removeClass('s' + p.sortorder);
                } else if ($(this).attr('abbr') == p.sortname) {
                    var no = (p.sortorder == 'asc') ? 'desc' : 'asc';
                    $('div', this).addClass('s' + p.sortorder).removeClass('s' + no);
                }
                if (g.colCopy) {
                    $(g.cdropleft).remove();
                    $(g.cdropright).remove();
                    g.dcolt = null;
                }
            }); //wrap content
        });
        //set bDiv
        g.bDiv.className = 'bDiv';
        $(t).before(g.bDiv);
        $(g.bDiv).css({
            height: (p.height == 'auto') ? 'auto' : p.height + "px"
        }).scroll(function (e) {
            g.scroll()
        }).append(t);
        if (p.height == 'auto') {
            $('table', g.bDiv).addClass('autoht');
        }
        //add td & row properties
        g.addCellProp();
        g.addRowProp();
        //set cDrag only if we are using it
        if (p.colResize === true) {
            var cdcol = $('thead tr:first th:first', g.hDiv).get(0);
            if (cdcol !== null) {
                g.cDrag.className = 'cDrag';
                g.cdpad = 0;
                g.cdpad += (isNaN(parseInt($('div', cdcol).css('borderLeftWidth'), 10)) ? 0 : parseInt($('div', cdcol).css('borderLeftWidth'), 10));
                g.cdpad += (isNaN(parseInt($('div', cdcol).css('borderRightWidth'), 10)) ? 0 : parseInt($('div', cdcol).css('borderRightWidth'), 10));
                g.cdpad += (isNaN(parseInt($('div', cdcol).css('paddingLeft'), 10)) ? 0 : parseInt($('div', cdcol).css('paddingLeft'), 10));
                g.cdpad += (isNaN(parseInt($('div', cdcol).css('paddingRight'), 10)) ? 0 : parseInt($('div', cdcol).css('paddingRight'), 10));
                g.cdpad += (isNaN(parseInt($(cdcol).css('borderLeftWidth'), 10)) ? 0 : parseInt($(cdcol).css('borderLeftWidth'), 10));
                g.cdpad += (isNaN(parseInt($(cdcol).css('borderRightWidth'), 10)) ? 0 : parseInt($(cdcol).css('borderRightWidth'), 10));
                g.cdpad += (isNaN(parseInt($(cdcol).css('paddingLeft'), 10)) ? 0 : parseInt($(cdcol).css('paddingLeft'), 10));
                g.cdpad += (isNaN(parseInt($(cdcol).css('paddingRight'), 10)) ? 0 : parseInt($(cdcol).css('paddingRight'), 10));
                $(g.bDiv).before(g.cDrag);
                var cdheight = $(g.bDiv).height();
                var hdheight = $(g.hDiv).height();
                $(g.cDrag).css({
                    top: -hdheight + 'px'
                });
                $('thead tr:first th', g.hDiv).each(function () {
                    var cgDiv = document.createElement('div');
                    $(g.cDrag).append(cgDiv);
                    if (!p.cgwidth) {
                        p.cgwidth = $(cgDiv).width();
                    }
                    $(cgDiv).css({
                        height: cdheight + hdheight
                    }).mousedown(function (e) {
                        g.dragStart('colresize', e, this);
                    }).dblclick(function (e) {
                        g.autoResizeColumn(this);
                    });
                    if (browser.msie && browser.version < 7.0) {
                        g.fixHeight($(g.gDiv).height());
                        $(cgDiv).hover(function () {
                            g.fixHeight();
                            $(this).addClass('dragging');
                        }, function () {
                            if (!g.colresize) {
                                $(this).removeClass('dragging');
                            }
                        });
                    }
                });
            }
        }
        //add strip
        if (p.striped) {
            $('tbody tr:odd', g.bDiv).addClass('erow');
        }
        if (p.resizable && p.height != 'auto') {
            g.vDiv.className = 'vGrip';
            $(g.vDiv).mousedown(function (e) {
                g.dragStart('vresize', e);
            }).html('<span></span>');
            $(g.bDiv).after(g.vDiv);
        }
        if (p.resizable && p.width != 'auto' && !p.nohresize) {
            g.rDiv.className = 'hGrip';
            $(g.rDiv).mousedown(function (e) {
                g.dragStart('vresize', e, true);
            }).html('<span></span>').css('height', $(g.gDiv).height());
            if (browser.msie && browser.version < 7.0) {
                $(g.rDiv).hover(function () {
                    $(this).addClass('hgOver');
                }, function () {
                    $(this).removeClass('hgOver');
                });
            }
            $(g.gDiv).append(g.rDiv);
        }
        // add pager
        if (p.usepager) {
            g.pDiv.className = 'pDiv';
            g.pDiv.innerHTML = '<div class="pDiv2"></div>';
            $(g.bDiv).after(g.pDiv);
            var html = ' <div class="pGroup"> <div class="pFirst pButton"><span></span></div><div class="pPrev pButton"><span></span></div> </div> <div class="btnseparator"></div> <div class="pGroup"><span class="pcontrol">' + p.pagetext + ' <input id="current_page" type="text" size="4" value="1" /> ' + p.outof + ' <span> 1 </span></span></div> <div class="btnseparator"></div> <div class="pGroup"> <div class="pNext pButton"><span></span></div><div class="pLast pButton"><span></span></div> </div> <div class="btnseparator"></div> <div class="pGroup"> <div class="pReload pButton"><span></span></div> </div> <div class="btnseparator"></div> <div class="pGroup"><span class="pPageStat"></span><span id="total_data" style="display:none;"></span></div>';
            $('div', g.pDiv).html(html);
            $('.pReload', g.pDiv).click(function () {
                g.populate();
            });
            $('.pFirst', g.pDiv).click(function () {
                g.changePage('first');
            });
            $('.pPrev', g.pDiv).click(function () {
                g.changePage('prev');
            });
            $('.pNext', g.pDiv).click(function () {
                g.changePage('next');
            });
            $('.pLast', g.pDiv).click(function () {
                g.changePage('last');
            });
            $('.pcontrol input', g.pDiv).keydown(function (e) {
                if (e.keyCode == 13) {
                    g.changePage('input');
                }
            });
            if (browser.msie && browser.version < 7)
                $('.pButton', g.pDiv).hover(function () {
                    $(this).addClass('pBtnOver');
                }, function () {
                    $(this).removeClass('pBtnOver');
                });
            if (p.useRp) {
                var opt = '',
                        sel = '';
                for (var nx = 0; nx < p.rpOptions.length; nx++) {
                    if (p.rp == p.rpOptions[nx])
                        sel = 'selected="selected"';
                    else
                        sel = '';
                    opt += "<option value='" + p.rpOptions[nx] + "' " + sel + " >" + p.rpOptions[nx] + "&nbsp;&nbsp;</option>";
                }
                $('.pDiv2', g.pDiv).prepend("<div class='pGroup'><select name='rp'>" + opt + "</select></div> <div class='btnseparator'></div>");
                $('select', g.pDiv).change(function () {
                    if (p.onRpChange) {
                        p.onRpChange(+this.value);
                    } else {
                        p.newp = 1;
                        p.rp = +this.value;
                        g.populate();
                    }
                });
            }
            //add search button
            if (p.searchitems) {
                $('.pDiv2', g.pDiv).prepend("<div class='pGroup'> <div id='" + t.id + "_pSearch' class='pSearch pButton'><span></span></div> </div>  <div class='btnseparator'></div>");
//                $('.pSearch', g.pDiv).click(function () {
//                    $(g.sDiv).slideToggle('fast', function () {
//                        $('.sDiv:visible input:first', g.gDiv).trigger('focus');
//                    });
//                });
                //add search box
                g.sDiv.className = 'sDiv';
                var sitems = p.searchitems;
                var sopt = '', sel = '';
                /* ADDITIONAL STARTS HERE / AWAL TAMBAHAN */
                var stext = '<span id="' + t.id + '_stext" style="display:none;" class="stext"><input type="text" value="' + p.query + '" size="30" name="q" class="qsbox" /></span>';
                var sdate = '<span id="' + t.id + '_sdate" style="display:none;" class="sdate"><input id="sfrom" type="text" size="20" value="' + p.date_start + '" name="qdatestart"> - <input id="sto" type="text" size="20" value="' + p.date_end + '" name="qdateend"></span>';
                var snum = '<span id="' + t.id + '_snum" style="display:none;" class="snum"><input id="snumfrom" type="text" size="20" value="' + p.num_start + '" name="qnumstart"> - <input id="snumto" type="text" size="20" value="' + p.num_end + '" name="qnumend"></span>';
                var sselect = '';
                for (var s = 0; s < sitems.length; s++) {
                    if (p.qtype === '' && sitems[s].isdefault === true) {
                        p.qtype = sitems[s].name;
                        sel = 'selected="selected"';
                    } else {
                        sel = '';
                    }
                    //sopt += "<option value='" + sitems[s].name + "' " + sel + " >" + sitems[s].display + "&nbsp;&nbsp;</option>";
                    sopt += "<option value='" + sitems[s].name + "' " + sel + " class='s" + sitems[s].type + "'>" + sitems[s].display + "&nbsp;&nbsp;</option>";

                    if (sitems[s].type == 'select') {
                        sselect += '<span id="sselect_' + sitems[s].name + '" style="display:none;" class="qselect"><select name="qoption" id="combo_' + sitems[s].name + '">';

                        var option_sel = '';
                        var arr_option = sitems[s].option.split('|');
                        for (var x = 0; x < arr_option.length; x++) {
                            var arr_value = arr_option[x].split(':');
                            if (p.option == arr_value[0]) {
                                //option_sel = 'selected="selected"';
                            } else {
                                //option_sel = '';
                            }
                            sselect += '<option value="' + arr_value[0] + '" ' + option_sel + '>' + arr_value[1] + '</option>';
                        }
                        sselect += '</select></span>';
                    }
                }
                if (p.qtype === '') {
                    p.qtype = sitems[0].name;
                }
                /*
                 $(g.sDiv).append("<div class='sDiv2'>" + p.findtext +
                 " <input type='text' value='" + p.query +"' size='30' name='q' class='qsbox' /> "+
                 " <select name='qtype'>" + sopt + "</select></div>");
                 */
                $(g.sDiv).append(
                        '<div class="sDiv2">' +
                        p.findtext +
                        /* changed */
                        ' <select name="qtype" id="sqtype">' + sopt + '</select>' +
                        ' <span id="sinput">' +
                        stext +
                        sdate +
                        snum +
                        sselect +
                        '&nbsp;&nbsp;<input type="button" value="' + p.findbuttontext + '" id="do_filter" class="sDiv2Button" />' +
                        '&nbsp;&nbsp;<input type="button" value="Clear Filter" id="clear_filter" class="sDiv3Button" />' +
                        '</span> ' +
                        '</div>' +
                        '');

                var colInput = '';
                for (var s = 0; s < sitems.length; s++) {
                    var collName = $(sitems[s]).attr('name');
                    var collDisplay = $(sitems[s]).attr('display');
                    var type = sitems[s].type;
                    colInput += '<div class="col-md-12">';
                    colInput += '<div class="form-group">';
                    if (type == 'text') {
                        colInput += '<label for="' + collName + '">' + collDisplay + '</label><input type="text" class="form-control querys querys_text" id="' + t.id + '_' + collName + '" placeholder="' + collDisplay + '" param="' + collName + '">';
                    } else if (type == 'num') {
                        colInput += '<label for="' + collName + '">' + collDisplay + '</label><input id="' + t.id + '_' + collName + '" type="text" class="form-control querys querys_num_start col-md-2" param="' + collName + '" placeholder="Min"> <input id="' + t.id + '_' + collName + '" type="text" class="form-control querys querys_num_end" param="' + collName + '" placeholder="Max">';
                    } else if (type == 'select') {
                        colInput += '<label for="' + collName + '">' + collDisplay + '</label>';
                        colInput += '<select class="form-control select2 querys querys_option" style="width: 100%" id="' + t.id + '_' + collName + '" param="' + collName + '">';
                        colInput += '<option value="">Choose ' + collDisplay + '</option>';
                        var option_sel = '';
                        var arr_option = sitems[s].option.split('|');
                        for (var x = 0; x < arr_option.length; x++) {
                            var arr_value = arr_option[x].split(':');
                            colInput += '<option value="' + arr_value[0] + '" ' + option_sel + '>' + arr_value[1] + '</option>';
                        }
                        colInput += '</select>';
                    } else if (type == 'date') {
                        colInput += '<label for="' + collName + '">' + collDisplay + '</label>';
                        colInput += '<div class="input-group date">';
                        colInput += '<input type="text" class="form-control hasDateRange querys querys_date" id="' + t.id + '_' + collName + '" param="' + collName + '">';
                        colInput += '<div class="input-group-addon">';
                        colInput += '<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>';
                        colInput += '</div>';
                        colInput += '</div>';
                    }

                    colInput += '</div>';
                    colInput += '</div>';
                }

                $('body').append(
                        '<div id="' + t.id + '_formModalFilter" class="modal fade formModalFilter" role="dialog" aria-labelledby="formModalLabel">' +
                        '<div class="modal-dialog" role="document">' +
                        '<div class="modal-content">' +
                        '<div class="modal-header">' +
                        '<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>' +
                        '<h4 class="modal-title" id="formModalLabel"><i class="fa fa-search"></i>&nbsp; Search Data</h4>' +
                        '</div>' +
                        '<div class="modal-body">' +
                        '<div class="block_form">' +
                        '<form>' +
                        '<div class="row">' + colInput +
                        '</div>' +
                        '</form></div></div>' +
                        '<div class="modal-footer">' +
                        '<center>' +
                        '<button type="button" class="btn btn-success btn_grid_filter"><i class="fa fa-search"></i>&nbsp; Search</button>' +
                        '<button id="' + t.id + '_reset" type="button" class="btn btn-danger"><i class="fa fa-refresh"></i>&nbsp; Reset</button>' +
                        '<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp; Close</button>' +
                        '</center>' +
                        '</div></div></div></div>'
                        );

                $("#"+ t.id + "_formModalFilter .select2").select2();

                //button reset pencarian
                $("#" + t.id + "_reset").click(function () {
                    $("#"+ t.id + "_formModalFilter input").val('');
                    $("#"+ t.id + "_formModalFilter select").val('').change();
                    $('#'+ t.id + '_formModalFilter').modal('hide');
                    g.doSearch();
                });

                $("#" + t.id + "_pSearch").click(function () {
                    $('.hasDateRange').daterangepicker({
                        "showDropdowns": true,
                        "customRangeLabel": "Custom",
                        "locale": {
                            "format": "YYYY-MM-DD"
                        },
                        "parentEl":$("#"+ t.id + "_formModalFilter")
                    });

                    $('.block_form').slimScroll({
                        height: '390px'
                    });
//                    if ($(this).hasClass('selected')) {
//                        deselect($(this));
//                    } else {
//                        $(this).addClass('selected');
//                        $('.pop').slideFadeToggle();
//                    }
//                    $('.close').on('click', function() {
//                        deselect($('.pSearch'));
//                        return false;
//                    });
                    $('#'+ t.id + '_formModalFilter').modal('toggle');
                    $('#' + t.id + '_formModalFilter .btn_grid_filter').click(function () {
                        $('#'+ t.id + '_formModalFilter').modal('hide');
                        g.doSearch();
                    });
                    $('#' + t.id + '_formModalFilter input.querys').keyup(function (e) {
                        if (e.keyCode == 13) {
                            $('#'+ t.id + '_formModalFilter').modal('hide');
                            g.doSearch();
                        }
                    });

                    var selectedtype = $("#sqtype option:selected").val();
                    if (selectedtype != '') {
                        var selectedtypeclass = $("#sqtype option:selected").attr('class');
                        var selectedvalue = $("#sqtype option:selected").val();

                        if (selectedtypeclass == 'stext') {
                            $("#" + t.id + "_stext").show();
                            $("#" + t.id + "_sdate").hide();
                            $("#" + t.id + "_snum").hide();
                            $(".qselect").hide();
                        } else if (selectedtypeclass == 'sdate') {
                            $("#" + t.id + "_sdate").show();
                            $("#" + t.id + "_stext").hide();
                            $("#" + t.id + "_snum").hide();
                            $(".qselect").hide();
                        } else if (selectedtypeclass == 'snum') {
                            $("#" + t.id + "_snum").show();
                            $("#" + t.id + "_stext").hide();
                            $("#" + t.id + "_sdate").hide();
                            $(".qselect").hide();
                        } else if (selectedtypeclass == 'sselect') {
                            $("#" + t.id + "_sdate").hide();
                            $("#" + t.id + "_stext").hide();
                            $("#" + t.id + "_snum").hide();
                            $(".qselect").hide();
                            $("#sselect_" + selectedvalue).show();
                        }
                    } else {
                        for (var z = 0; z < sitems.length; z++) {
                            if (sitems[z].isdefault == true) {
                                if (sitems[z].type == 'text') {
                                    $("#" + t.id + "_stext").show();
                                    $("#" + t.id + "_sdate").hide();
                                    $("#" + t.id + "_snum").hide();
                                    $(".qselect").hide();
                                } else if (sitems[z].type == 'date') {
                                    $("#" + t.id + "_sdate").show();
                                    $("#" + t.id + "_stext").hide();
                                    $("#" + t.id + "_snum").hide();
                                    $(".qselect").hide();
                                } else if (sitems[z].type == 'num') {
                                    $("#" + t.id + "_snum").show();
                                    $("#" + t.id + "_stext").hide();
                                    $("#" + t.id + "_sdate").hide();
                                    $(".qselect").hide();
                                } else if (sitems[z].type == 'select') {
                                    $("#" + t.id + "_sdate").hide();
                                    $("#" + t.id + "_stext").hide();
                                    $("#" + t.id + "_snum").hide();
                                    $(".qselect").hide();
                                    $("#sselect_" + selectedvalue).show();
                                }
                            }
                        }
                    }
                });

                //Split into separate selectors because of bug in jQuery 1.3.2
                $('input[name=q]', g.sDiv).keydown(function (e) {
                    if (e.keyCode == 13) {
                        g.doSearch();
                    }
                });
                $('select[name=qtype]', g.sDiv).keydown(function (e) {
                    if (e.keyCode == 13) {
                        g.doSearch();
                    }
                });
                $('input[id=do_filter]', g.sDiv).click(function () {
                    g.doSearch();
                });
                /*
                 $('input[value=Clear]', g.sDiv).click(function () {
                 $('input[name=q]', g.sDiv).val('');
                 p.query = '';
                 g.doSearch();
                 });
                 */
                $('input[id=clear_filter]', g.sDiv).click(function () {
                    $('input[name=q]', g.sDiv).val('');
                    $('input[name=qdatestart]', g.sDiv).val('');
                    $('input[name=qdateend]', g.sDiv).val('');
                    $('input[name=qnumstart]', g.sDiv).val('');
                    $('input[name=qnumend]', g.sDiv).val('');
                    $('.qselect option').removeAttr('selected');
                    p.query = '';
                    p.option = '';
                    p.optionused = false;
                    p.date_start = '';
                    p.date_end = '';
                    p.num_start = '';
                    p.num_end = '';
                    g.doClear();
                });
                $(g.bDiv).after(g.sDiv);

                $("#sqtype").change(function () {
                    var selectedtype = $("#sqtype option:selected").attr('class');
                    var selectedvalue = $("#sqtype option:selected").val();
                    if (selectedtype == 'stext') {
                        $("#" + t.id + "_stext").show();
                        $("#" + t.id + "_sdate").hide();
                        $("#" + t.id + "_snum").hide();
                        $(".qselect").hide();
                        $('input[name=q]', g.sDiv).val('');
                        $('input[name=qdatestart]', g.sDiv).val('');
                        $('input[name=qdateend]', g.sDiv).val('');
                        $('input[name=qnumstart]', g.sDiv).val('');
                        $('input[name=qnumend]', g.sDiv).val('');
                        $('input[name=q]', g.sDiv).focus();
                    } else if (selectedtype == 'sdate') {
                        $("#" + t.id + "_sdate").show();
                        $("#" + t.id + "_stext").hide();
                        $("#" + t.id + "_snum").hide();
                        $(".qselect").hide();
                        $('input[name=q]', g.sDiv).val('');
                        $('input[name=qdatestart]', g.sDiv).val('');
                        $('input[name=qdateend]', g.sDiv).val('');
                        $('input[name=qnumstart]', g.sDiv).val('');
                        $('input[name=qnumend]', g.sDiv).val('');
                        $('input[name=qdatestart]', g.sDiv).focus();
                    } else if (selectedtype == 'snum') {
                        $("#" + t.id + "_snum").show();
                        $("#" + t.id + "_stext").hide();
                        $("#" + t.id + "_sdate").hide();
                        $(".qselect").hide();
                        $('input[name=q]', g.sDiv).val('');
                        $('input[name=qdatestart]', g.sDiv).val('');
                        $('input[name=qdateend]', g.sDiv).val('');
                        $('input[name=qnumstart]', g.sDiv).val('');
                        $('input[name=qnumend]', g.sDiv).val('');
                        $('input[name=qnumstart]', g.sDiv).focus();
                    } else if (selectedtype == 'sselect') {
                        $("#" + t.id + "_sdate").hide();
                        $("#" + t.id + "_stext").hide();
                        $("#" + t.id + "_snum").hide();
                        $(".qselect").hide();
                        $("#sselect_" + selectedvalue).show();
                        $('input[name=q]', g.sDiv).val('');
                        $('input[name=qdatestart]', g.sDiv).val('');
                        $('input[name=qdateend]', g.sDiv).val('');
                        $('input[name=qnumstart]', g.sDiv).val('');
                        $('input[name=qnumend]', g.sDiv).val('');
                        $("#sselect_" + selectedvalue + " select").focus();
                    }
                });

                $("#sfrom").datepicker({
                    defaultDate: "+0d",
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: 'yy-mm-dd',
                    onSelect: function (selectedDate) {
                        $("#sto").datepicker("option", "minDate", selectedDate);
                        if ($("#sto").val() == '') {
                            $("#sto").val($("#sfrom").val());
                        }
                    }
                });

                $("#sto").datepicker({
                    defaultDate: "+0d",
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: 'yy-mm-dd',
                    onSelect: function (selectedDate) {
                        $("#sfrom").datepicker("option", "maxDate", selectedDate);
                    }
                });

                $("#snumfrom").blur(function () {
                    var num_from = $("#snumfrom").val();
                    if ($("#snumto").val() == '') {
                        $("#snumto").val(num_from);
                    }
                });
                /* ADDITIONAL ENDS HERE / AKHIR TAMBAHAN */
            }
        }
        $(g.pDiv, g.sDiv).append("<div style='clear:both'></div>");
        // add title
        if (p.title) {
            var class_title = '';
            if (p.title_class) {
                class_title = p.title_class;
            }

            g.mDiv.className = 'mDiv';
            g.mDiv.innerHTML = '<div class="ftitle ' + class_title + '">' + p.title + '</div>';
            $(g.gDiv).prepend(g.mDiv);
            if (p.showTableToggleBtn) {
                $(g.mDiv).append('<div class="ptogtitle" title="Minimize/Maximize Table"><span></span></div>');
                $('div.ptogtitle', g.mDiv).click(function () {
                    $(g.gDiv).toggleClass('hideBody');
                    $(this).toggleClass('vsble');
                });
            }
        }
        //setup cdrops
        g.cdropleft = document.createElement('span');
        g.cdropleft.className = 'cdropleft';
        g.cdropright = document.createElement('span');
        g.cdropright.className = 'cdropright';
        //add block
        g.block.className = 'gBlock';
        var gh = $(g.bDiv).height();
        var gtop = g.bDiv.offsetTop;
        $(g.block).css({
            width: g.bDiv.style.width,
            height: gh,
            background: 'white',
            position: 'relative',
            marginBottom: (gh * -1),
            zIndex: 1,
            top: gtop,
            left: '0px'
        });
        $(g.block).fadeTo(0, p.blockOpacity);
        // add column control
        if ($('th', g.hDiv).length) {
            g.nDiv.className = 'nDiv';
            g.nDiv.innerHTML = "<table cellpadding='0' cellspacing='0'><tbody></tbody></table>";
            $(g.nDiv).css({
                marginBottom: (gh * -1),
                display: 'none',
                top: gtop
            }).noSelect();

//            $('tbody', g.nDiv).append('<tr><td class="ndcol1" colspan="2"><input type="text" name="querys" class="addcolsearch" title="'+cm.name+'" value="" /></td></tr>');
//
//            $('input.addcolsearch', g.nDiv).keyup(function(e) {
//                if (e.keyCode == 13) {
//                    g.doSearch();
//                }
//            });

            var cn = 0;
            $('th div', g.hDiv).each(function () {
                var kcol = $("th[axis='col" + cn + "']", g.hDiv)[0];
                var chk = 'checked="checked"';
                if (kcol.style.display == 'none') {
                    chk = '';
                }
                $('tbody', g.nDiv).append('<tr><td class="ndcol1"><input type="checkbox" ' + chk + ' class="togCol" value="' + cn + '" /></td><td class="ndcol2">' + this.innerHTML + '</td></tr>');
                cn++;
            });
            if (browser.msie && browser.version < 7.0)
                $('tr', g.nDiv).hover(function () {
                    $(this).addClass('ndcolover');
                }, function () {
                    $(this).removeClass('ndcolover');
                });
            $('td.ndcol2', g.nDiv).click(function () {
                if ($('input:checked', g.nDiv).length <= p.minColToggle && $(this).prev().find('input')[0].checked)
                    return false;
                return g.toggleCol($(this).prev().find('input').val());
            });
            $('input.togCol', g.nDiv).click(function () {
                if ($('input:checked', g.nDiv).length < p.minColToggle && this.checked === false)
                    return false;
                $(this).parent().next().trigger('click');
            });
            $(g.gDiv).prepend(g.nDiv);
            $(g.nBtn).addClass('nBtn')
                    .html('<div></div>')
                    .attr('title', 'Hide/Show Columns')
                    .click(function () {
                        $(g.nDiv).toggle();
                        return true;
                    }
                    );
            if (p.showToggleBtn) {
                $(g.gDiv).prepend(g.nBtn);
            }
        }
        // add date edit layer
        $(g.iDiv).addClass('iDiv').css({
            display: 'none'
        });
        $(g.bDiv).append(g.iDiv);
        // add flexigrid events
        $(g.bDiv).hover(function () {
            $(g.nDiv).hide();
            $(g.nBtn).hide();
        }, function () {
            if (g.multisel) {
                g.multisel = false;
            }
        });
        $(g.gDiv).hover(function () {}, function () {
            $(g.nDiv).hide();
            $(g.nBtn).hide();
        });
        //add document events
        $(document).mousemove(function (e) {
            g.dragMove(e);
        }).mouseup(function (e) {
            g.dragEnd();
        }).hover(function () {}, function () {
            g.dragEnd();
        });
        //browser adjustments
        if (browser.msie && browser.version < 7.0) {
            $('.hDiv,.bDiv,.mDiv,.pDiv,.vGrip,.tDiv, .sDiv', g.gDiv).css({
                width: '100%'
            });
            $(g.gDiv).addClass('ie6');
            if (p.width != 'auto') {
                $(g.gDiv).addClass('ie6fullwidthbug');
            }
        }
        g.rePosDrag();
        g.fixHeight();
        //make grid functions accessible
        t.p = p;
        t.grid = g;
        // load data
        if (p.url && p.autoload) {
            g.populate();
        }
        return t;
    };
    var docloaded = false;
    $(document).ready(function () {
        docloaded = true;
    });
    $.fn.flexigrid = function (p) {
        return this.each(function () {
            if (!docloaded) {
                $(this).hide();
                var t = this;
                $(document).ready(function () {
                    $.addFlex(t, p);
                });
            } else {
                $.addFlex(this, p);
            }
        });
    }; //end flexigrid
    $.fn.flexReload = function (p) { // function to reload grid
        return this.each(function () {
            if (this.grid && this.p.url){
                this.grid.populate();
            }
        });
    }; //end flexClearReload
    $.fn.flexClearReload = function (p) { // function to clear and reload grid
        return this.each(function () {
            if (this.grid && this.p.url) {
                this.grid.doClear();
                // this.grid.populate();
            }
        });
    }; //end flexClearReload
    $.fn.flexOptions = function (p) { //function to update general options
        return this.each(function () {
            if (this.grid)
                $.extend(this.p, p);
        });
    }; //end flexOptions
    $.fn.flexToggleCol = function (cid, visible) { // function to reload grid
        return this.each(function () {
            if (this.grid)
                this.grid.toggleCol(cid, visible);
        });
    }; //end flexToggleCol
    $.fn.flexAddData = function (data) { // function to add data to grid
        return this.each(function () {
            if (this.grid)
                this.grid.addData(data);
        });
    };
    $.fn.noSelect = function (p) { //no select plugin by me :-)
        if (p === undefined || p === null) {
            p = true;
        } else {
            p = false;
        }
        var prevent = (p == null) ? true : p;
        if (prevent) {
            return this.each(function () {
                if (browser.msie || browser.safari) {
                    $(this).bind('selectstart', function () {
                        return false;
                    });
                } else if (browser.mozilla) {
                    $(this).css('MozUserSelect', 'none');
                    $('body').trigger('focus');
                } else if (browser.opera) {
                    $(this).bind('mousedown', function () {
                        return false;
                    });
                } else {
                    $(this).attr('unselectable', 'on');
                }
            });
        } else {
            return this.each(function () {
                if (browser.msie || browser.safari) {
                    $(this).unbind('selectstart');
                } else if (browser.mozilla) {
                    $(this).css('MozUserSelect', 'inherit');
                } else if (browser.opera) {
                    $(this).unbind('mousedown');
                } else {
                    $(this).removeAttr('unselectable', 'on');
                }
            });
        }
    }; //end noSelect
    $.fn.flexSearch = function (p) { // function to search grid
        return this.each(function () {
            if (this.grid && this.p.searchitems)
                this.grid.doSearch();
        });
    }; //end flexSearch
    $.fn.selectedRows = function (p) { // Returns the selected rows as an array, taken and adapted from http://stackoverflow.com/questions/11868404/flexigrid-get-selected-row-columns-values
        var arReturn = [];
        var arRow = [];
        var selector = $(this.selector + ' .trSelected');


        $(selector).each(function (i, row) {
            arRow = [];
            var idr = $(row).data('id');
            $.each(row.cells, function (c, cell) {
                var col = cell.abbr;
                var val = cell.firstChild.innerHTML;
                if (val == '&nbsp;')
                    val = '';      // Trim the content
                var idx = cell.cellIndex;

                arRow.push({
                    Column: col, // Column identifier
                    Value: val, // Column value
                    CellIndex: idx, // Cell index
                    RowIdentifier: idr  // Identifier of this row element
                });
            });
            arReturn.push(arRow);
        });
        return arReturn;
    };
})(jQuery);

function nl2br(str, is_xhtml) {
    var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
    if (str != '' && str != null) {
        return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
    } else {
        return '';
    }

}

function check(com, grid, urlaction) {
    if (com == 'selectall') {
        $('.bDiv tbody tr', grid).addClass('trSelected');
    } else if (com == 'selectnone') {
        $('.bDiv tbody tr', grid).removeClass('trSelected');
    }
}

function redirect(com, grid, urlaction) {
    if (urlaction != '') {
        window.location.href = urlaction;
    } else {
        alert('Redirect failed!');
    }
}

function act_show(com, grid, urlaction) {
    var grid_id = $(grid).attr('id');
    grid_id = grid_id.substring(grid_id.lastIndexOf('grid_') + 5);
    if (urlaction == '') {
        urlaction = 'act_show';
    }

    if ($('.trSelected', grid).length > 0) {
        var title = '';
        if (com == 'delete') {
            title = 'Delete';
        } else if (com == 'publish') {
            title = 'Activate';
        } else if (com == 'unpublish') {
            title = 'Nonactivate';
        } else if (com == 'approve') {
            title = 'Approve';
        } else if (com == 'reject') {
            title = 'Reject';
        } else if (com == 'process') {
            title = 'Process';
        } else if (com == 'review') {
            title = 'Review';
        }

        var conf = confirm('Are you sure to ' + title + ' ' + $('.trSelected', grid).length + ' data?\nThis action cannot be undone!');
        if (conf == true) {
            var arr_id = [];
            var i = 0;
            $('.trSelected', grid).each(function () {
                var id = $(this).attr('data-id');
                arr_id.push(id);
                i++;
            });
            $.ajax({
                type: 'POST',
                url: urlaction,
                data: com + '=true&item=' + JSON.stringify(arr_id),
                dataType: 'json',
                success: function (response) {
                    $('#' + grid_id).flexReload();
                    if (response['message'] != '') {
                        var message_class = response['message_class'];
                        if (message_class == '') {
                            message_class = 'response_confirmation alert alert-success';
                        }
                        $("#response_message").finish();
                        $("#response_message").addClass(message_class);
                        $("#response_message").slideDown("fast");
                        $("#response_message").html(response['message']);
                        $("#response_message").delay(10000).slideUp(1000, function () {
                            $("#response_message").removeClass(message_class);
                        });
                    }
                }
            });
        }
    }else{
        alert('You haven\'t selected data!');
    }
}

function act_sort(com, grid, urlaction) {
    var grid_id = $(grid).attr('id');
    grid_id = grid_id.substring(grid_id.lastIndexOf('grid_') + 5);
    if (urlaction == '') {
        urlaction = 'act_show';
    }

    if ($('.trSelected', grid).length > 0) {
        var arr_id = [];
        var i = 0;
        $('.trSelected', grid).each(function () {
            var id = $(this).attr('data-id');
            arr_id.push(id);
            i++;
        });
        $.ajax({
            type: 'POST',
            url: urlaction,
            data: com + '=true&item=' + JSON.stringify(arr_id),
            dataType: 'json',
            success: function (response) {
                $('#' + grid_id).flexReload();
                if (response['message'] != '') {
                    var message_class = response['message_class'];
                    if (message_class == '') {
                        message_class = 'response_confirmation alert alert-success';
                    }
                    $("#response_message").finish();
                    $("#response_message").addClass(message_class);
                    $("#response_message").slideDown("fast");
                    $("#response_message").html(response['message']);
                    $("#response_message").delay(5000).slideUp(1000, function () {
                        $("#response_message").removeClass(message_class);
                    });
                }
            }
        });
    }
}

function export_data(com, grid, urlaction) {
    var arr_column_name = [{}];
    var arr_column_title = [{}];
    var arr_column_show = [{}];
    var arr_column_align = [{}];
    var qselectused = false;
    var optionused = false;
    var selectedoption = '';
    var option = '';
    $(".sDiv .qselect", grid).each(function () {
        var id = $(this).attr('id');
        var show = $("#" + id).is(':hidden');
        if (show == false) {
            qselectused = true;
            selectedoption = $("#" + id + " select[name=qoption] option:selected").val();
        }
    });

    if (qselectused == true) {
        option = selectedoption;
        optionused = true;
    } else {
        option = '';
        optionused = false;
    }
    
    var querys = [];
    $('.querys').each(function () {
        if ($(this).val() != '') {
            if ($(this).hasClass('querys_text')) {
                querys.push({
                    filter_type: 'querys_text',
                    filter_field: $(this).attr('param'),
                    filter_value: $(this).val()
                });
            } else if ($(this).hasClass('querys_num')) {
                console.log($(this));
                querys.push({
                    filter_type: 'querys_num',
                    filter_field: $(this).attr('param'),
                    filter_value: $(this).val()
                });
            } else if ($(this).hasClass('querys_option')) {
                querys.push({
                    filter_type: 'querys_option',
                    filter_field: $(this).attr('param'),
                    filter_value: $(this).val()
                });
            } else if ($(this).hasClass('querys_date')) {
                querys.push({
                    filter_type: 'querys_date',
                    filter_field: $(this).attr('param'),
                    filter_value: $(this).val()
                });
            }
        }
    });
    querys = JSON.stringify(querys);
    
    var query = $(".sDiv input[name=q]", grid).val();
    var date_start = $(".sDiv input[name=qdatestart]", grid).val();
    var date_end = $('.sDiv input[name=qdateend]', grid).val();
    var qtype = $(".sDiv select[name=qtype]", grid).val();
    var rp = $(".pDiv select[name=rp]", grid).val();
    var page = $(".pDiv #current_page", grid).val();
    var total_data = $(".pDiv #total_data", grid).html();
    var sortname = $(grid).attr('data-sortname');
    var sortorder = $(grid).attr('data-sortorder');

    var i = 0;
    $('.hDiv tr th', grid).each(function () {
        var column_name = $(this).attr('abbr');
        var column_title = $(this).children('div:first-child').html();
        var attr_hidden = $(this).attr('hidden');
        var attr_align = $(this).attr('align');

        arr_column_name[i] = column_name;
        arr_column_title[i] = column_title;

        if ((typeof attr_hidden !== 'undefined' && attr_hidden !== false) || $(this).hasClass('no_datasource')) {
            arr_column_show[i] = false;
        } else {
            arr_column_show[i] = true;
        }

        if (typeof attr_align !== 'undefined' && attr_align !== false) {
            arr_column_align[i] = attr_align;
        } else {
            arr_column_align[i] = 'left';
        }
        i++;
    });

    if (urlaction == '') {
        urlaction = 'export_data';
    }

    $form = $("<form target='_blank' method='post' action='" + urlaction + "'></form>");
    $form.append("<input id='export_column_name' type='hidden' name='column[name]' value='" + JSON.stringify(arr_column_name) + "' />");
    $form.append("<input id='export_column_title' type='hidden' name='column[title]' value='" + JSON.stringify(arr_column_title) + "' />");
    $form.append("<input id='export_column_show' type='hidden' name='column[show]' value='" + JSON.stringify(arr_column_show) + "' />");
    $form.append("<input id='export_column_align' type='hidden' name='column[align]' value='" + JSON.stringify(arr_column_align) + "' />");
    $form.append("<input id='export_sortname' type='hidden' name='params[sortname]' value='" + sortname + "' />");
    $form.append("<input id='export_sortorder' type='hidden' name='params[sortorder]' value='" + sortorder + "' />");
    $form.append("<input id='export_query' type='hidden' name='params[query]' value='" + query + "' />");
    $form.append("<input id='export_querys' type='hidden' name='params[querys]' value='" + querys + "' />");
    $form.append("<input id='export_optionused' type='hidden' name='params[optionused]' value='" + optionused + "' />");
    $form.append("<input id='export_option' type='hidden' name='params[option]' value='" + option + "' />");
    $form.append("<input id='export_date_start' type='hidden' name='params[date_start]' value='" + date_start + "' />");
    $form.append("<input id='export_date_end' type='hidden' name='params[date_end]' value='" + date_end + "' />");
    $form.append("<input id='export_qtype' type='hidden' name='params[qtype]' value='" + qtype + "' />");
    $form.append("<input id='export_total_data' type='hidden' name='params[total_data]' value='" + total_data + "' />");
    $form.append("<input id='export_rp' type='hidden' name='params[rp]' value='" + rp + "' />");
    $form.append("<input id='export_page' type='hidden' name='params[page]' value='" + page + "' />");
    $(grid).after($form);
    $form.submit();
}
