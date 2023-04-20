function selectTab(tabname){
    switch (tabname) {
        case "change_password":
            document.getElementById('change_password').style.display = "block";
            document.getElementById('change_profile_picture').style.display = "none";
            document.getElementById('personal_info').style.display = "none";
            document.getElementsByClassName('tabcontent').style.display = "block"
            document.cookie = "tabname" + tabname
            break;

        case "change_profile_picture":
            document.getElementById('change_profile_picture').style.display = "block";
            document.getElementById('change_password').style.display = "none"
            document.getElementById('personal_info').style.display = "none"
            document.getElementsByClassName('tabcontent').style.display = "block"
            document.cookie = "tabname" + tabname
            break;

        case "personal_info":
            document.getElementById('personal_info').style.display = "block";
            document.getElementById('change_profile_picture').style.display = "none"
            document.getElementById('change_password').style.display = "none";
            document.getElementsByClassName('tabcontent').style.display = "block"
            document.cookie = "tabname" + tabname
            break;

        
    }
}


function switchFunction(mode){
    if(document.getElementById("").checked)
}


let twoFA = "active" // cookie
let mode = false; //cookie?

function checkFA(cookie){
    switch (cookie) {
        case "active":
            if(!mode){
                document.getElementById('container').style.display = "none";
                document.getElementById('confirm').style.display = "block";
            }else{
                document.getElementById('container').style.display = "block";
                document.getElementById('confirm').style.display = "none";
            }
            break;
    
        case "disabled":
            document.getElementById('container').style.display = "block";
            document.getElementById('confirm').style.display = "none";
        break;
    }
}


//  confirm
//  container