export const TableRows = (params) => {
    const { columns, dataSource, settings } = params
    const x = dataSource.map((line, index) => {
        const { trClassList } = settings
        const y = columns.map((element, index) => {
            const { dataIndex } = element
            return `<td>${line[dataIndex]}</td>`
        });
        return `<tr class="${trClassList}">${y.join('')}</tr>`
    })

    // console.log(x)
    return x.join('')
}