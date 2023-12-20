export const TableColGroup = (params) => {
    const { columns } = params
    const col = []
    for (let i = 0; i < columns.length; i++) {
        const { dataIndex, width = 100, hidden } = columns[i]
        if (hidden) continue
        const styleStr = `style="width:${width}px"`
        col.push(`<col name="${dataIndex}" ${styleStr}>`)
    }
    return `<colgroup>${col.join('')}</colgroup>`
}