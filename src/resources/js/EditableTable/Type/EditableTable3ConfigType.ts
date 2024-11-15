export interface VirtualScrollParams {
    rowHeight: number
    viewportHeight: number
    bufferSize: number
}

export interface TableConfig {
    width?: number
    maxH?: number | null
    borderColor?: string
    showPaginationTop?: boolean
    showPaginationBottom?: boolean
    showNo?: boolean
    tableDebug?: boolean
    tableTrueWidth?: boolean

    tableHeader?: string
    tableFooter?: string

    topLeftControl?: string
    topCenterControl?: string
    topRightControl?: string

    bottomLeftControl?: string
    bottomCenterControl?: string
    bottomRightControl?: string

    rotate45Width?: number
    rotate45Height?: number

    classList?: {
        text: string
        textarea: string
        dropdown: string
        toggle: string
        toggle_checkbox: string
        button: string
        dropdown_fake: string
    }

    virtualScroll?: VirtualScrollParams
    animationDelay?: number

    //This will be remove as MODE is more flexible to column level
    // editable?: boolean
}
