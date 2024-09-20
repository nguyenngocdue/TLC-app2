addLinesToTableFormModalList = (listId, tableId, xxxForeignKeys, dataTypeToGetId, modalId) => {
    const result = $('#' + listId)
        .val()
        .split(',')
        .map((x) => x * 1)
    console.log('Adding result now:', listId, result)
    const xxxForeignKeyArray = xxxForeignKeys.split(',').map((x) => x.trim())
    console.log('xxxForeignKeyArray', xxxForeignKeyArray)
    console.log('dataTypeToGetId', dataTypeToGetId)
    // console.log('jsonTree', jsonTree)

    const linesOfCorrectType = []
    for (let i = 0; i < jsonTree.length; i++) {
        if (jsonTree[i].data.type == dataTypeToGetId) {
            linesOfCorrectType.push(jsonTree[i])
        }
    }
    console.log('linesOfCorrectType', linesOfCorrectType)

    const strongKey = xxxForeignKeyArray[0] // can be ID or finger_print
    const toBeAdded = []
    for (let i = 0; i < linesOfCorrectType.length; i++) {
        const line = linesOfCorrectType[i]
        // console.log(strongKey, result, line.data)
        if (result.includes(line.data[strongKey])) {
            // console.log("Adding line", line)
            toBeAdded.push(line)
        }
        // console.log('result', line.data[strongKey], result)
    }

    console.log('toBeAdded', toBeAdded, 'with strongKey', strongKey)

    for (let i = 0; i < toBeAdded.length; i++) {
        // const today = moment().format('DD/MM/YYYY')
        // const valuesOfOrigin = { [xxxForeignKeys]: result[i]}
        const valuesOfOrigin = {}
        for (let j = 0; j < xxxForeignKeyArray.length; j++) {
            valuesOfOrigin[xxxForeignKeyArray[j]] = toBeAdded[i].data[xxxForeignKeyArray[j]]
        }
        console.log('Add line', tableId, valuesOfOrigin)
        addANewLine({ tableId, valuesOfOrigin, isDuplicatedOrAddFromList: true, batchLength: toBeAdded.length })
    }
}
