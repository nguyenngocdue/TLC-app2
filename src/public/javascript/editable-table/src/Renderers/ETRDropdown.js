export const ETRDropdown = (cell, column) => {
    // console.log(column.dataSource)
    const content = column.dataSource[cell]?.['name'] || `_[${cell}]_`
    return `${content}`
}