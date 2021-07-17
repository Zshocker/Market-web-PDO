function checkoneName(str) 
{   
   str=str.split('');
   for(var i=0;i<str.length;i++)
   {
    var asci = str[i].charCodeAt(0);
    if(str[i]==' '||str[i]=='-' || str[i]=='_')continue;
    if (asci < 'A'.charCodeAt(0)) return false; 
    if (asci > 'Z'.charCodeAt(0)) {
        if (asci < 'a'.charCodeAt(0)) return false;
        if (asci > 'z'.charCodeAt(0)) return false;
    }
   }
   return true;
}
function check_Empty(str)
{
    var V=document.getElementById(str).value;
    if(V=="")return false;
    return true;
}
function show_elem_id(str) 
{
    document.getElementById(str).style.display = "block";
}
function unshow_elem_id(str) 
{
    document.getElementById(str).style.display = "none";
}
function add_hidden_value_id(id,value,idHid)
{
    var doc=document.getElementById(id);
    doc.innerHTML += '<input type="hidden" id="'+ idHid +'" name="id_prod" value="'+value+'">';
}
function remove_html_by_id(id)
{
    document.getElementById(id).remove();
}
function switchSrcImg(idF,idI)
{
    document.getElementById(idF).src=document.getElementById(idI).src;
}
function test_name(name){
const regex=/^([a-zA-Z]+\ ?)+$/;
return regex.test(name);
}
function test_numMar(name){
const regex=/0[567][0-9]{8}/;
return regex.test(name);
}
function test_Email(email){
         const regex= /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
          return regex.test(email);
}
