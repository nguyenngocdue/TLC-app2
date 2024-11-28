// export interface VirtualScrollParams {
//     rowHeight: number
//     viewportHeight: number
//     // bufferSize: number
// }

import { ToolbarComponents } from '../ToolbarComponents'

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

    topLeftControl?: keyof typeof ToolbarComponents
    topCenterControl?: keyof typeof ToolbarComponents
    topRightControl?: keyof typeof ToolbarComponents

    bottomLeftControl?: keyof typeof ToolbarComponents
    bottomCenterControl?: keyof typeof ToolbarComponents
    bottomRightControl?: keyof typeof ToolbarComponents

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

    // virtualScroll?: VirtualScrollParams
    animationDelay?: number
    rowHeight?: number

    orderable?: boolean
    duplicatable?: boolean
    deletable?: boolean

    uploadServiceEndpoint?: string

    showButton?: {
        AddAnItem?: boolean
        AddFromList?: boolean
        CloneFromTemplate?: boolean
        Recalculate?: boolean
    }

    entityLineType: string
    envConfig?: {
        entityParentType?: string
        entityParentId?: string
        currentUserId?: string
        entityProjectId?: string
        entitySubProjectId?: string

        tableNames?: { [table01Name: string]: string }
        tableFnNames?: { [table01Name: string]: string }
    }

    //This will be remove as MODE is more flexible to column level
    // editable?: boolean
}
