export const getEById = (id) => $("[id='" + id + "']")
// Custom function for loose comparison
export const looseInclude = (array, value) => array.some(item => item == value)

export const helloList = (count = 100) => {
    const result = {}
    for (let i = 0; i < count; i++) {
        result[`a${i + 1}`] = { name: `Hello ${i + 1}` }
    }
    return result
}