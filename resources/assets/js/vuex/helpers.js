export function addRecords (records, newRecords, type, callback) {
  if (!newRecords) {
    return
  }

  newRecords.forEach(newRecord => {
    addRecord(records, newRecord, type, callback)
  })
}

export function addRecord (records, newRecord, type, callback) {
  if(!newRecord || newRecord.type != type) {
    return
  }

  var record = records.find(r => r.id === newRecord.id)
  
  if (!record) {
    records.push(newRecord)
  } else if (callback) {
    record.attributes = newRecord.attributes
    record.links = newRecord.links
    
    callback(record, newRecord)
  } else {
    record.attributes = newRecord.attributes
    record.links = newRecord.links

    if(!newRecord.relationships) {
      record.relationships = {};
      return;
    }

    record.relationships = newRecord.relationships ? 
      newRecord.relationships : record.relationships
  }
}