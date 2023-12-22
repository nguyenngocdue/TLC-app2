const itemRenderer1 = (cell, column) => column.dataSource[cell]?.['name'] || `_[${cell}]_`

export const ETRDropdown = (cell, column) => {
    // console.log(column.dataSource)
    const {
        itemRenderer = itemRenderer1,
    } = column

    return itemRenderer(cell, column)
    // const rendered = cells.map(cell => itemRenderer(cell, column))
    // return rendered.join("<br/>")
}