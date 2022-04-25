
async function  get_zip(a){


const url = "https://dawa.aws.dk/postnumre/"


const response = await fetch(url+a);
const data = await response.json();

    
return await data.navn;

}


async function ziplistener(a, b){

    //a gets value from zip field
    //b gets the id of the field where the result is to be put

  let result = await get_zip(a);

  result = result === undefined ? '' : result;
  
  document.getElementById(b).setAttribute('value', result );

  


}