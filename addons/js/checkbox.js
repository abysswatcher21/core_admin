
function check_all(input_name, start) {
    master = document.getElementById(input_name);

    i = start;
    input_element = input_name + "_"+ i;
    input_obj = document.getElementById(input_element);
    while(input_obj != null)
    {
        input_obj = document.getElementById(input_element);
        input_obj.checked = master.checked;

        i = i + 1;
        input_element = input_name + "_"+ i;
        input_obj = document.getElementById(input_element);
    }
}

function check_parent(parent_id, child_id) {
    if(child_id.checked) {
        parent_id.checked = true;
    }
}