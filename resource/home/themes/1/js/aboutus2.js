// JavaScript Document
window.onload=function(){
		var oUl=document.getElementById('cont_ul');
		var oLi=oUl.getElementsByTagName('li');
		
		for(var i=0;i<oLi.length;i++)
		{
			oLi[i].onmouseover=function(){
				this.style.backgroundColor='teal';
			}
			oLi[i].onmouseout=function(){
				this.style.backgroundColor='#fff';
			}
		}
	}