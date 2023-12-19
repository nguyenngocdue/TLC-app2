export const TableRows = ({ columns, dataSource }) => {

    const x = dataSource.map((line, index) => {
        const y = columns.map((element, index) => {
            const { dataIndex } = element
            return `<td>${line[dataIndex]}</td>`
        });
        return `<tr>${y.join('')}</tr>`
    })

    // console.log(x)
    return x.join('')
}