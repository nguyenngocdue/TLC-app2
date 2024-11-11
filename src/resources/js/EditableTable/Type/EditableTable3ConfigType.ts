export interface TableConfig {
    width?: number
    maxH?: number | null
    borderColor?: string
    showPaginationTop?: boolean
    showPaginationBottom?: boolean
    showNo?: boolean
    tableDebug?: boolean

    tableHeader?: string
    tableFooter?: string

    topLeftControl?: string
    topCenterControl?: string
    topRightControl?: string

    bottomLeftControl?: string
    bottomCenterControl?: string
    bottomRightControl?: string
}
