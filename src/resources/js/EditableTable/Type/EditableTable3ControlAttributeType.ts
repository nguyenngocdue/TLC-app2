export interface ControlAttributeNo {}
export interface ControlAttributeAction {}
export interface ControlAttributeActionCheckbox {}
export interface ControlAttributeActionPrint {}
export interface ControlAttributeQrCode {}
export interface ControlAttributeDocId {}
export interface ControlAttributeIdLink {
    entityName: string
}
export interface ControlAttributeStatus {}
export interface ControlAttributeIdStatus {
    statusColumn?: string
    nameColumn?: string
}

export interface ControlAttributeParentLink {}
export interface ControlAttributeColumn {
    columnToLoad?: string
}

export interface ControlAttributeHyperLink {
    target?: '_blank' | '_self' | '_parent' | '_top'
}
export interface ControlAttributeAggCount {
    unit?: string
    columnToLoad?: string
}
export interface ControlAttributeAggCount {}

export interface ControlAttributeAvatarUser {
    maxToShow?: number
}
export interface ControlAttributeAttachment {
    maxToShow?: number
    maxPerLine?: number

    fileType?: string
    maxFileCount?: number
    maxFileSize?: number

    // categoryId?: number
    // subCategoryId?: number
    fieldName?: string
    groupId?: number

    // objectType: string
    // objectId: number | string
    // uploadServiceEndpoint?: string

    uploadable?: boolean
    deletable?: boolean
    // showUploader?: boolean
    // showUploadDate?: boolean
}

export interface ControlAttributeCustomFunction {}
export interface ControlAttributeText {}
export interface ControlAttributeTextarea {
    rowCount?: number
}
export interface ControlAttributeNumber {
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
    pickerType?: 'date' | 'time' | 'datetime' | 'month' | 'year'
    format?: string
    minDate?: string
    maxDate?: string
}

export interface ControlAttributeSearchableDialog {
    isMultiple?: boolean
}
