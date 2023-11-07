function buildCrossword(crossword, words_list) {

    let crosswordHTMLElement = document.getElementById('crossword')

    for (const crosswordRow of crossword) {
        let row = document.createElement('div')
        row.className = 'row'
        crosswordHTMLElement.append(row)
        for (const item of crosswordRow) {

            let rowItem = document.createElement('div')
            rowItem.className = 'row-item'
            rowItem.textContent = item[0]

            row.appendChild(rowItem)

        }
    }



    for (const word of words_list) {
        let row = document.createElement('div')
        row.style.display = 'flex'
        row.textContent = word
        crosswordHTMLElement.append(row)
    }

}

async function crossword() {

    // let body = {
    //     words: ["DOMEN", "MADE", "POPA", "PET", "NAME"],
    //     x: 5,
    //     y: 5
    // }

    let response = await fetch('http://crossword-api/api/crossword/1', {
        method: 'GET',
        headers: {
            // Authorization: 'Bearer 1|xttyPlT3dPjREE6qOozOTahAVo8XNA1AIl2JLPHjee7604c4',
            "Content-Type": "application/json"
        },
        // body: JSON.stringify(body),
    })

    let data = await response.json();

    buildCrossword(JSON.parse(data.crossword), JSON.parse(data.words))

}


crossword()

// console.log(response)


