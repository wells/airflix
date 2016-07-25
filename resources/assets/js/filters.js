export function filterByGenre (items, id) {
  return items.filter(function(item) {
  	const record = item.relationships.genres.data.find(g => g.id === id)
    return id == '' || record != null
  })
}

export function filterByHasMovies (items) {
  return items.filter(function(item) {
    return item.attributes.total_movies > 0
  })
}

export function filterByHasShows (items) {
  return items.filter(function(item) {
    return item.attributes.total_shows > 0
  })
}

export function zeroPad (value, padding) {
    var absoluteValue = Math.abs(value);
    var places = Math.max(0, padding - Math.floor(absoluteValue).toString().length );
    var paddedString = Math.pow(10, places).toString().substr(1);
    if( value < 0 ) {
        paddedString = '-' + paddedString;
    }

    return paddedString + absoluteValue;
}