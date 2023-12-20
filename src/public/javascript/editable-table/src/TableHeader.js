export const TableHeader = (params) => {
    const { columns, settings } = params
    const { thead_tr } = settings.cssClass
    const ths = []
    for (let i = 0; i < columns.length; i++) {
        const { dataIndex, title, hidden } = columns[i]
        if (hidden) continue
        ths.push(`<th class="border" title="${dataIndex}">${title}</th>`)
    }
    return `<thead class="sticky z-10 top-0">
        <tr class="${thead_tr}">
            ${ths.join('')}
        </tr>
    </thead>`
}