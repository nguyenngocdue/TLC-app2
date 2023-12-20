import { getFixedClass } from "./FrozenColumn"

export const TableHeader = (params) => {
    const { columns, settings } = params
    const { thead_tr } = settings.cssClass
    const ths = []
    for (let i = 0; i < columns.length; i++) {
        const { dataIndex, title, hidden } = columns[i]
        if (hidden) continue
        const fixedClass = getFixedClass(columns[i], i, 'th')
        ths.push(`<th class="border ${fixedClass}" title="${dataIndex}">${title}</th>`)
    }
    return `<thead class="sticky z-10 top-0">
        <tr class="${thead_tr}">
            ${ths.join('')}
        </tr>
    </thead>`
}