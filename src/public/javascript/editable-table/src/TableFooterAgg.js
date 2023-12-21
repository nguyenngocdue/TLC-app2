import { getFixedClass } from "./FrozenColumn"

const aggCalculate = (column, dataSource, tableFnName) => {
    const { footer, dataIndex } = column
    const array = dataSource.map(item => item[dataIndex])

    const result = {
        agg_sum: array.reduce((accumulator, currentValue) => accumulator + (typeof currentValue === 'number' ? currentValue : 0), 0),
        agg_count: array.filter(i => i).length,
    }

    const inputs = Object.keys(result).map(value => `<input 
        tabindex="-1"
        class="w-full border-none text-right text-sm bg-gray-100"
        readonly
        type="${value === footer ? 'text' : 'hidden'}" 
        name="${tableFnName}[footer][${dataIndex}][${value}]" 
        value="${result[value]}" 
    />`)
    return inputs.join('')
}

export const TableFooterAgg = (params) => {
    const { columns, settings, dataSource, tableParams } = params
    const { tfoot_tr } = settings.cssClass
    const { tableFnName, tableId } = tableParams
    const ths = []
    // for (let i = 0; i < columns.length; i++) {
    Object.keys(columns).map((dataIndex, i) => {
        const column = columns[dataIndex]
        const { hidden, width = 100 } = column
        if (hidden) return
        const content = aggCalculate(column, dataSource, tableFnName)
        const fixedClass = getFixedClass(column, i, 'th', tableId)
        const styleStr = `style="width:${width}px"`
        ths.push(`<th class="border ${fixedClass}" ${styleStr}>${content}</th>`)
    })
    return `<tfoot>
        <tr class="${tfoot_tr}">
            ${ths.join('')}
        </tr>
    </tfoot>`
}