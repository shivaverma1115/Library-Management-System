document.addEventListener('DOMContentLoaded',()=>{
 document.querySelectorAll('form').forEach(form=>{
  form.addEventListener('submit',e=>{
   if(form.dataset.confirm && !confirm(form.dataset.confirm)) e.preventDefault();
  });
 });
 document.querySelectorAll('[data-autohide]').forEach(el=>setTimeout(()=>el.remove(),5000));
});
