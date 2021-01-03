function set_cookie(){
    let date = new Date();
    let cookieName = 'rc_gdpr_cookie';
    let cookieValue = 'dismiss';
    date.setTime(date.getTime()+(1500*24*60*60*1000));
    document.cookie = cookieName + "=" + cookieValue + "; expires=" + date.toGMTString();
}

function rc_gdpr_dismiss(){
    let box = document.getElementsByClassName("rc_gdpr_box");
    box[0].className += " rc_gdpr_fade_out";
    set_cookie();
}