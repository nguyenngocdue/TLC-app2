const aggs = ['agg_sum', 'agg_count']

const aggCalculate = (column, dataSource) => {
    const { footer, dataIndex } = column
    const array = dataSource.map(item => item[dataIndex])

    // if(Array.isArray())

    const result = {
        agg_sum: array.reduce((accumulator, currentValue) => accumulator + (typeof currentValue === 'number' ? currentValue : 0), 0),
        agg_count: array.filter(i => i).length,
    }

    const inputs = Object.keys(result).map(value => `<input 
        class="w-full border-none text-right text-sm bg-gray-100"
        readonly
        type="${value === footer ? 'text' : 'hidden'}" 
        name="getProdRuns[footer][${value}]" 
        value="${result[value]}" 
    />`)
    return inputs.join('')
}

export const TableFooterAgg = (params) => {
    const { columns, settings, dataSource } = params
    const { tfoot_tr } = settings.cssClass
    const ths = []
    for (let i = 0; i < columns.length; i++) {
        const { hidden } = columns[i]
        if (hidden) continue
        const content = aggCalculate(columns[i], dataSource)
        ths.push(`<th class="border">${content}</th>`)
    }
    return `<tfoot>
        <tr class="${tfoot_tr}">
            ${ths.join('')}
        </tr>
    </tfoot>`
}