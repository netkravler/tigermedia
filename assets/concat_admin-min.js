function get_modal(e){var t=document.getElementById("myModal"),l=document.getElementById(e),n=document.getElementById("img01"),i=document.getElementById("caption");t.style.display="block",n.src=l.src,i.innerHTML=l.alt,document.getElementsByClassName("close")[0].onclick=function(){t.style.display="none"}}function SetSel(e,t){var l=document.getElementsByName(t),n=e.checked,c=l.length;for(i=0;i<c;i++)"checkbox"===l[i].type&&(l[i].checked=!1);e.checked=n}function hide_tags(e){var t=document.getElementById(e);"none"===t.style.display?t.style.display="block":t.style.display="none"}window.addEventListener("load",function(){let e=document.querySelectorAll('[role="rowgroup"]');for(i=0;i<e.length;i++){let t=e[i].querySelectorAll(".flex-row"),l="num_cols"+t.length;for(y=0;y<t.length;y++)t[y].classList.add(l)}var t=document.querySelectorAll("ul.nav-tabs > li");for(i=0;i<t.length;i++)t[i].addEventListener("click",l);function l(e){e.preventDefault(),document.querySelector("ul.nav-tabs li.active").classList.remove("active"),document.querySelector(".tab-pane.active").classList.remove("active");var t=e.currentTarget,l=e.target.getAttribute("href");t.classList.add("active"),document.querySelector(l).classList.add("active")}}),jQuery(document).ready(function(e){e(document).on("click",".js-image-upload",function(t){t.preventDefault();let l=e(this),n=wp.media.frames.file_frame=wp.media({title:"Select or Upload image",library:{type:"image"},button:{text:"Select image"},multiple:!1});n.on("select",function(){let e=n.state().get("selection").first().toJSON();l.siblings(".image-upload").val(e.url)}),n.open()})});