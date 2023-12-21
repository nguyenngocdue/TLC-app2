export const ETRDropdown = (cell, column) => {
    // console.log(column.dataSource)
    const content = column.dataSourceIndexed[cell]?.['name'] || `_[${cell}]_`
    return `${content}`
}