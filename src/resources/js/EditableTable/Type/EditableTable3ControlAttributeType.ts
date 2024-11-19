export interface ControlAttributeNo {
    align?: 'left' | 'center' | 'right'
}
export interface ControlAttributeAction {
    align?: 'left' | 'center' | 'right'
}
export interface ControlAttributeActionCheckbox {
    align?: 'left' | 'center' | 'right'
}
export interface ControlAttributeActionPrint {
    align?: 'left' | 'center' | 'right'
}
export interface ControlAttributeQrCode {
    align?: 'left' | 'center' | 'right'
}
export interface ControlAttributeDocId {
    align?: 'left' | 'center' | 'right'
}
export interface ControlAttributeIdLink {
    align?: 'left' | 'center' | 'right'
    entityName: string
}
export interface ControlAttributeStatus {
    align?: 'left' | 'center' | 'right'
}
export interface ControlAttributeIdStatus {
    align?: 'left' | 'center' | 'right'
}
export interface ControlAttributeIdStatusLink {
    align?: 'left' | 'center' | 'right'
}
export interface ControlAttributeParentLink {
    align?: 'left' | 'center' | 'right'
}
export interface ControlAttributeColumn {
    align?: 'left' | 'center' | 'right'
}
export interface ControlAttributeColumnLink {
    align?: 'left' | 'center' | 'right'
}
export interface ControlAttributeHyperLink {
    align?: 'left' | 'center' | 'right'
    target?: '_blank' | '_self' | '_parent' | '_top'
}
export interface ControlAttributeAggCount {
    align?: 'left' | 'center' | 'right'
}
export interface ControlAttributeAggCount {
    align?: 'left' | 'center' | 'right'
}

export interface ControlAttributeAvatarUser {
    align?: 'left' | 'center' | 'right'
}
export interface ControlAttributeThumbnail {
    align?: 'left' | 'center' | 'right'
}
export interface ControlAttributeThumbnails {
    align?: 'left' | 'center' | 'right'
}
export interface ControlAttributeCustomFunction {
    align?: 'left' | 'center' | 'right'
}
export interface ControlAttributeText {
    align?: 'left' | 'center' | 'right'
}
export interface ControlAttributeTextarea {
    align?: 'left' | 'center' | 'right'
    rowCount?: number
}
export interface ControlAttributeNumber {
    align?: 'left' | 'center' | 'right'
    decimalPlaces?: number
}
export interface ControlAttributeDropdown {
    allowClear?: boolean
    allowChooseWhenOneItem?: boolean
    allowOpen?: boolean
    sortBy?: string
    dataSource?: { [key11: string]: { [key22: string]: string | number } }
    dataSourceKey?: string

    valueField?: string
    labelField?: string
    descriptionField?: string
    tooltipField?: string
    avatarField?: string

    filterColumns?: string
    filterOperator?: string
    filterValues?: string
    isMultiple?: boolean
}
export interface ControlAttributeToggle {}
export interface ControlAttributeCheckbox {}
export interface ControlAttributePickerDatetime {
    align?: 'left' | 'center' | 'right'
    pickerType?: 'date' | 'time' | 'datetime' | 'month' | 'year'
    format?: string
    minDate?: string
    maxDate?: string
}

export interface ControlAttributeAttachment {
    fileType?: string
}

export interface ControlAttributeSearchableDialog {
    isMultiple?: boolean
}
