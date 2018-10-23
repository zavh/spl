var count = 2, interval, el = document.getElementById('autologout');
interval = setInterval(
            function(){ 
                autoLogOut(); 
            }, 
            1000);
function autoLogOut(){
    count--;
    el.innerHTML = count;
    if(count<2){
        clearInterval(interval);
        document.getElementById('logout-form').submit();
    }
}