
async function  get_zip(a){


  const url = "https://dawa.aws.dk/postnumre/"
  
  
  const response = await fetch(url+a);
  const data = await response.json();
  
      
  return await data.navn;
  
  }
  
  
  async function ziplistener(a, b){
  
      //a gets value from zip field
      //b gets the id of the field where the result is to be put
  
      if(document.getElementById(b).value != ''){

        document.getElementById(b).value='';
   
      }
   

    
    document.getElementById(b).setAttribute('value', await get_zip(a) );
  
    
  
  
  }