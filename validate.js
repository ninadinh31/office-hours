window.onsubmit = validateRegistration;
/*function main(){
    document.getElementById("subReg").onclick = validateRegistration;
}
main();*/

function invalid_name(name, maxLength){
    if(name.length > maxLength ){
        return true;
    }
    for(i=0;i<name.length;i++){
        asciiNum = name.charCodeAt(i);
        if(asciiNum<65 || (asciiNum > 90 && asciiNum < 97) || asciiNum > 122){
            return true;
        }
    }

    return false;
}

function invalid_uid(uid){
    
    for(i=0;i<uid.length;i++){
        asciiNum = uid.charCodeAt(i);
        alert(asciiNum);
        if(asciiNum < 48 || (asciiNum > 57 && asciiNum<65) || (asciiNum > 90 && asciiNum < 97) || asciiNum > 122 || asciiNum === 45){
            return true;
        }
    }
    return false;
}

function validateRegistration(){
    firstname = document.getElementById("firstname").value;
    lastname = document.getElementById("lastname").value;
    uid = document.getElementById("uid").value;
    alertMessage = "";

    if(invalid_name(firstname, 25)){
        alertMessage += "First name must only contain letters and be less than 25 characters long\n";
    }
    if(invalid_name(lastname, 30)){
        alertMessage += "Last name must only contain letters and be less than 30 characters long\n";
    }
    if(invalid_uid(uid)){
        alertMessage += "A valid uid contains only letters and/or numbers\n";
    }

    if(alertMessage === ""){
        if(window.confirm("Do you want to create this user?")) {
            return true;
        } else {
            return false;
        }
    } else {
        alert(alertMessage);
        return false;
    }


}