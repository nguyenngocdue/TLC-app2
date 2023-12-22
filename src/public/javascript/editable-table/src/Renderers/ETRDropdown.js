const itemRenderer1 = (cell, column) => column.dataSource[cell]?.['name'] || `_[${cell}]_`

export const ETRDropdown = (cell, column) => {
    // console.log(column.dataSource)
    const {
        multiple = false,
        itemRenderer = itemRenderer1,
    } = column
    return itemRenderer(cell, column)
}