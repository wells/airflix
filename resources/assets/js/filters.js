export function zeroPad (value, padding) {
  var absoluteValue = Math.abs(value);
  var places = Math.max(0, padding - Math.floor(absoluteValue).toString().length );
  var paddedString = Math.pow(10, places).toString().substr(1);
  if( value < 0 ) {
    paddedString = '-' + paddedString;
  }

  return paddedString + absoluteValue;
}