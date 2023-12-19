import { DataSource } from './DataSource'

export const TableRows = ({ columns, dataSource }) => {

    const x = dataSource.map((line, index) => {
        const { trClassList } = DataSource.TableSettings
        const y = columns.map((element, index) => {
            const { dataIndex } = element
            return `<td>${line[dataIndex]}</td>`
        });
        return `<tr class="${trClassList}">${y.join('')}</tr>`
    })

    // console.log(x)
    return x.join('')
}