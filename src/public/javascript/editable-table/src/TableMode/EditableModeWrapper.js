import { EditableMode } from "./EditableMode"

export const EditableModeWrapper = (params) => {
    const { header, footer, showPaginationTop, showPaginationBottom } = params.tableParams
    const { topLeftControls, topCenterControls, topRightControls } = params.tableParams
    const { bottomLeftControls, bottomCenterControls, bottomRightControls } = params.tableParams

    const editableMode = EditableMode(params)
    const cssClassHeader = `border-t bg-gray-100 px-4 py-3 text-xs font-semibold1 tracking-wide text-blue-500 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 sm:grid-cols-9`
    const cssClassFooter = `border-t bg-gray-100 px-4 py-3 text-xs font-semibold1 tracking-wide text-blue-500 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 sm:grid-cols-9`
    const cssClass2 = `w-full grid grid-cols-12 border-b border-red-50 bg-gray-100 px-4 py-3 text-xs font-semibold1 tracking-wide text-gray-500 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300`
    const cssClass3 = `w-full grid grid-cols-12 border-b border-red-50 bg-gray-100 px-4 py-3 text-xs font-semibold1 tracking-wide text-gray-500 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300`
    const cssClassControl = `lg:col-span-4 md:col-span-12 flex gap-1`

    const topBar = `<div class="${cssClass2}">
        <span class="${cssClassControl}">${topLeftControls}</span>
        <span class="${cssClassControl} justify-center">${topCenterControls}</span>
        <span class="${cssClassControl} justify-end">${topRightControls}</span>
    </div>`

    const bottomBar = `<div class="${cssClass3}">
        <span class="${cssClassControl}">${bottomLeftControls}</span>
        <span class="${cssClassControl} justify-center">${bottomCenterControls}</span>
        <span class="${cssClassControl} justify-end">${bottomRightControls}</span>
    </div>`

    return `<div class="border border-gray-300 dark:border-gray-600 ">
        <div>
            <div class="inline-block1 w-full sm:px-0 lg:px-0 ">
                ${header ? `<div class="${cssClassHeader} border-b">${header}</div>` : ''}
                ${showPaginationTop ? `${topBar}` : ``}
                ${editableMode}
                ${showPaginationBottom ? `${bottomBar}` : ``}
                ${footer ? `<div class="${cssClassFooter}">${footer}</div>` : ''}
            </div>
        </div>
    </div>
    `
}