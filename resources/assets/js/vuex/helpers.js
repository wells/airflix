export function addRecords (records, newRecords, type) {
  if (!newRecords) {
    return
  }

  newRecords.forEach(newRecord => {
    addRecord(records, newRecord, type)
  })
}

export function addRecord (records, newRecord, type, callback) {
  if(!newRecord || newRecord.type != type) {
    return
  }

  // Find record index (if any)
  let index = records.findIndex(r => r.id == newRecord.id)

  // Add record
  if (index == -1) {
    records.push(newRecord)
    return
  }

  // Update record with callback
  if (callback) {
    records[index].attributes = newRecord.attributes
    records[index].links = newRecord.links

    callback(records[index], newRecord)
    return
  }

  // Replace record
  records.splice(index, 1, newRecord)
}
