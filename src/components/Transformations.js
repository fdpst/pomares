function localizeNumber(num){
  return num.toFixed(2).replace('.',',');
};
function localizePrice(num){
  return  localizeNumber(num)+' €';
};
export {localizeNumber,localizePrice};


