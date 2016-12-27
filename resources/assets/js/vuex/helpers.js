export function addRecords (records, newRecords, type) {
  if (!newRecords) {
    return
  }

  newRecords.forEach(newRecord => {
    addRecord(records, newRecord, type)
  })
}

export function addRecord (records, newRecord, type) {
  if(!newRecord || newRecord.type != type) {
    return
  }

  // Find record index (if any)
  let index = records.findIndex(r => r.id == newRecord.id)

  // Update record
  if (index !== -1) {
    records[index] = newRecord
    return
  }

  // Add record
  records.push(newRecord)
}