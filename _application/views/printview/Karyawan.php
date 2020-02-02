<html>
    <head>
        <title>Print Purchase Order</title>
    </head>
    <body>
        <table width="100%">
            <tr>
                <td width="20%" style="text-align: center;">
                    <img src="<?php echo site_url('media/assets/images/printview/60/80/genesis.jpg'); ?>" alt="" >
                </td>
                <td width="60%">
                    <table width="100%">
                        <tr>
                            <td style="font-size:14pt;font-weight: bold; text-align: center;">Genesis Svargaloka Creative Hub</td>
                        </tr>
                        <tr>
                            <td style="font-size:8pt; text-align: center;">Address: Mantup, Baturetno, Banguntapan, Bantul Regency, Special Region of Yogyakarta 55197
</td>
                        </tr>
                        <tr>
                            <td style="font-size:8pt; text-align: center;">Phone Number : (0274) 8000022  </td>
                        </tr>
                    </table>
                </td>
       
                 
        
                
                    <td width="20%" style="text-align:center;">
                        <table width="99%">
                            <tr style="font-size: 5pt"><td></td></tr>
                            <tr>
                                <td width="95%" style="font-size:10pt; padding:2px 15px 2px 15px; border:solid 1px #222; text-align:center;"><strong>Data Karyawan</strong></td>
                            </tr>
    
                        </table>
                    </td>
                
            
            </tr>
            <tr>
                <td colspan="3" style="border-bottom: solid 1px #222; font-size:2pt;"></td>
            </tr>

            <!-- Jarak -->
            <tr>
                <td colspan="3">
                    <br>
                </td>
            </tr>

            <!-- Keterangan Penerima -->
            <tr>
                <td colspan="3">
                    <table width="99%" style="font-size:8pt;">
                        <tr>
                            <td>
                                <table width="99%" cellpadding="1">
                               
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <!-- Jarak -->
            <tr style="font-size:5pt;">
                <td colspan="3">
                    <br>
                </td>
            </tr>

            <!-- Daftar produk -->
            <tr>
                <td colspan="3">
                    <table width="99%" border="1" cellpadding="3" style="border-collapse:collapse;">
                        <tr style="font-size:8pt; background-color:#EDEDEE;">
                            <th style="padding:8px;" align="center" width="5%"><strong>#</strong></th>
                            <th style="padding:8px;" align="center" width="25%"><strong>NAMA KARYAWAN</strong></th>
                            <th style="padding:8px;" align="center" width="50%"><strong>ALAMAT KARYAWAN</strong></th>
                            <th style="padding:8px;" align="center" width="20%"><strong>GAJIH KARYAWAN</strong></th>
                        </tr>
                    <?php 
                    $no = 1;
                    foreach($officer as $karyawan){ ?>    
                        <tr style="font-size:8pt">
                                    <td style="padding:5px;" align="center"><?php echo $no++; ?></td>
                                    <td style="padding:5px;">&nbsp;<?php echo $karyawan->nama_karyawan; ?> </td>
                                    <td style="padding:5px;" align="center"><?php echo $karyawan->alamat_karyawan; ?></td>
                                    <td style="padding:5px;" align="center"><?php echo number_format($karyawan->gajih_karyawan,2,',','.'); ?></td>
                                   
                        </tr>
                    <?php } ?>
                    </table>
                </td>
            </tr>

            <!-- Jarak -->
            <tr style="font-size:5pt;">
                <td colspan="3">
                    <br>
                </td>
            </tr>

            <!-- Jarak -->
            <tr style="font-size:8pt;">
                <td colspan="3">
                    <br><br><br><br>
                </td>
            </tr>

            <tr style="font-size:8pt;">
                <td colspan="3">
                    <table width="100%" style="font-size:8pt;">
                      
                        <!-- Jarak -->
                        <tr>
                            <td>
                                <br />
                                <br />
                                <br />
                                <br />
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td align="center">( ................................................. )</td>
                            <td></td>
                            <td align="center"></td>
                            <td></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>