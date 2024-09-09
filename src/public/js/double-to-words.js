function numberToWords(number) {
    function capitalizeFirstLetter(str) {
        return str.replace(/(^|\s)\w/g, (match) => match.toUpperCase())
    }

    function readDecimalPart(number) {
        function convertDigitToWord(digit) {
            const digitsMap = {
                0: 'zero',
                1: 'one',
                2: 'two',
                3: 'three',
                4: 'four',
                5: 'five',
                6: 'six',
                7: 'seven',
                8: 'eight',
                9: 'nine',
            }
            return digitsMap[digit]
        }
        const numberString = number + ''
        const decimalPart = numberString.split('.')[1]
        if (decimalPart * 1 == 0) return ''
        if (!decimalPart) {
            return ''
        }
        let result = ['POINT']
        for (let i = 0; i < decimalPart.length; i++) {
            const digit = parseInt(decimalPart[i])
            const word = convertDigitToWord(digit)
            result.push(word)
        }
        return ' ' + result.join(' ')
    }

    if (typeof number == 'string') number = number.replace(/[^0-9.\-]/g, '')
    number = (number * 1).toString() * 1
    return capitalizeFirstLetter(window.toWords(number) + readDecimalPart(number))
}

function numberToWordsVn(number) {
    function capitalizeFirstLetter(str) {
        return str.replace(/(^|\s)\w/g, (match) => match.toUpperCase())
    }

    function readDecimalPart(number) {
        function convertDigitToWord(digit) {
            const digitsMap = {
                0: 'không',
                1: 'một',
                2: 'hai',
                3: 'ba ',
                4: 'bốn',
                5: 'năm',
                6: 'sáu',
                7: 'bảy',
                8: 'tám',
                9: 'chín',
            }
            return digitsMap[digit]
        }
        const numberString = number + ''
        const decimalPart = numberString.split('.')[1]
        if (decimalPart * 1 == 0) return ''
        if (!decimalPart) {
            return ''
        }
        let result = ['PHẨY']
        for (let i = 0; i < decimalPart.length; i++) {
            const digit = parseInt(decimalPart[i])
            const word = convertDigitToWord(digit)
            result.push(word)
        }
        return ' ' + result.join(' ')
    }

    if (typeof number == 'string') number = number.replace(/[^0-9.\-]/g, '')
    number = (number * 1).toString()

    const nguyen = VNnum2words(number.substring(0, number.indexOf('.')))
    const thapphan = readDecimalPart(number.substring(number.indexOf('.')))
    // console.log(nguyen, thapphan)
    return capitalizeFirstLetter(nguyen + thapphan)
}
