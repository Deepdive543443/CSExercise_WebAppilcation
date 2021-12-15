var username_accepted = false;
var password_accepted = false;
var password_retyped = false;
var forename_accept = false;
var surname_accept = false;
var email_accepted = false;
var email_retyped = false;

window.onload = function(){
    var username = document.getElementById('usr');
    var password = document.getElementById('pass');
    var retype_password = document.getElementById('repass');
    var forename = document.getElementById('fore');
    var surname = document.getElementById('sur');
    var email = document.getElementById('mail');
    var retype_email = document.getElementById('mail2');


    username.value = '';
    password.value = '';
    retype_password.value = '';
    forename.value = '';
    surname.value = '';
    email.value = '';
    retype_email.value = '';
    
    

    password.onkeyup = function(){
        var pwd = this.value;
        var result = 0;
        for(var i = 0, len = pwd.length; i < len; ++i){
            result |= charType(pwd.charCodeAt(i));
        }

        var level = 0;
        for(var i = 0; i <= 4; i++){
            if(result & 1){
                level ++;
            }
            result = result >>> 1;
        }

        if(pwd.length >= 6){
            var strength = document.getElementById("txtPassword1");
            switch (level) {
                case 1:
                    strength.src = "icons/icons8-lock-level1.png";
                    break;
                case 2:
                    strength.src = "icons/icons8-lock-level2.png";
                    break;
                case 3:
                case 4:
                    strength.src = "icons/icons8-lock-level3.png";
                    break;
            }
            strength.height= 24;
            password_accepted = true;          
        }else{
            var strength = document.getElementById("txtPassword1");
            strength.src = "icons/icons8-dots-loading.gif";
            strength.height= 24;
            password_accepted = false;
        }
        submitable();
    }

    retype_password.onkeyup = function(){
        var pwd = password.value;
        var repwd = this.value;
        if(repwd == pwd && password_accepted){
            password_retyped = true;
            var strength = document.getElementById("txtPassword2");
            strength.src = "icons/icons8-done-96.png";
            strength.height= 24;
        }else{
            password_retyped = false;
            var strength = document.getElementById("txtPassword2");
            strength.src = "icons/icons8-dots-loading.gif";
            strength.height= 24;
        }
        submitable();
    }
    
    username.onkeyup = function(){
        var input = this.value;
        if(input.length>= 3){
            username_accepted = true;
            var strength = document.getElementById("txtUsername");
            strength.src = "icons/icons8-done-96.png";
            strength.height= 24;
        }else{
            username_accepted = false;
            var strength = document.getElementById("txtUsername");
            strength.src = "icons/icons8-dots-loading.gif";
            strength.height= 24;
        } 
        submitable();
    }

    forename.onkeyup = function(){
        var input = this.value; 
        if(input.length > 0){
            forename_accept = true;
            var strength = document.getElementById("txtForename");
            strength.src = "icons/icons8-done-96.png";
            strength.height= 24;
        }else{
            forename_accept = false;
            var strength = document.getElementById("txtForename");
            strength.src = "icons/icons8-dots-loading.gif";
            strength.height= 24;
        } 
        submitable();
    }

    surname.onkeyup = function(){
        var input = this.value;
        if(input.length > 0){
            surname_accept = true;
            var strength = document.getElementById("txtSurname");
            strength.src = "icons/icons8-done-96.png";
            strength.height= 24;
        }else{
            surname_accept = false;
            var strength = document.getElementById("txtSurname");
            strength.src = "icons/icons8-dots-loading.gif";
            strength.height= 24;
        } 
        submitable();
    }

    email.onkeyup = function(){
        var email_front = false;
        var email_address_prescript = false;
        var email_address_postscript = false;
        var prescriptTagger;
        var postscirptTagger;


        var input = this.value;
        for(var i = 0, len = input.length; i < len;i++){
            console.log(input[i])
            if (i >= 1 && input[i] == "@"){
                email_front = true;
            }

            if (email_front && input[i] == "."){
                prescriptTagger = i;
                
                email_address_prescript = true;
            }

            var postscirptTagger = i;
            console.log("Pre: "+prescriptTagger);
            console.log("Post: "+postscirptTagger);
            if(email_address_prescript && postscirptTagger >= prescriptTagger + 2){
                console.log(i);
                email_address_postscript = true;     
            }
        }
     
        if(email_front && email_address_prescript && email_address_postscript){
            email_accepted = true;
            var strength = document.getElementById("txtEmail1");
            strength.src = "icons/icons8-done-96.png";
            strength.height= 24;
        }else{
            email_accepted = false;
            var strength = document.getElementById("txtEmail1");
            strength.src = "icons/icons8-dots-loading.gif";
            strength.height= 24;
        } 
        submitable();
    }

    retype_email.onkeyup = function(){
        var mail1 = email.value;
        var input = this.value;
        if(mail1 == input && email_accepted){
            email_retyped = true;
            var strength = document.getElementById("txtEmail2");
            strength.src = "icons/icons8-done-96.png";
            strength.height= 24;
        }else{
            email_retyped = false;
            var strength = document.getElementById("txtEmail2");
            strength.src = "icons/icons8-dots-loading.gif";
            strength.height= 24;
        } 
        submitable();
    }
}


function charType(num){
    if(num >= 48 && num <= 57){
        return 1;
    }
    if (num >= 97 && num <= 122) {
        return 2;
    }
    if (num >= 65 && num <= 90) {
        return 4;
    }
    return 8;
}

function submitable(){
    var submitBtn = document.getElementById('submit_button');
    if (username_accepted && password_accepted && password_retyped && forename_accept && surname_accept && email_accepted && email_retyped){
        
        submitBtn.disabled = false;
        submitBtn.className = "submit_button";
    }else{
        submitBtn.disabled = true;
        submitBtn.className = "unsubmit_button";
    }
}
