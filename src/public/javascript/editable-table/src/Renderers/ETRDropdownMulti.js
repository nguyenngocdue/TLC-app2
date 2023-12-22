const itemRenderer1 = (cell, column) => column.dataSource[cell]?.['name'] || `_[${cell}]_`

export const ETRDropdownMulti = (cell, column) => {
    // console.log(column.dataSource)
    const {
        multiple = false,
        itemRenderer = itemRenderer1,
    } = column
    const cells = cell

    const rendered = (cells && Array.isArray(cells)) ? cells.map(cell => itemRenderer(cell, column)) : ["_CELLS_?_"]
    return rendered.join("<br/>")
}