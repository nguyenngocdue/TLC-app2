const itemRenderer1 = (cell, column) => column.dataSource[cell]?.['name'] || `_[${cell}]_`

export const ETRDropdown = (cell, column) => {
    // console.log(column.dataSource)
    const {
        multiple = false,
        itemRenderer = itemRenderer1,
    } = column
    const cells = multiple ? cell : [cell]

    return cells.map(cell => itemRenderer(cell, column))
}