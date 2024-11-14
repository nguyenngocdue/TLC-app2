export interface ControlAttributeNo {
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
