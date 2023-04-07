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

//   visibility: visible;
//  visibility: hidden;