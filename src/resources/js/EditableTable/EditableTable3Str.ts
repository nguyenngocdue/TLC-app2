export class Str {
    static toHeadline = (text: string | number): string => {
        return text
            .toString()
            .toLowerCase()
            .split(/[\s_]+/) // Split by spaces or underscores
            .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
            .join(' ')
    }
}
// Usage
// console.log(Str.toHeadline('hello_world_test')) // "Hello World Test"
// console.log(Str.toHeadline('another example_string')) // "Another Example String"
