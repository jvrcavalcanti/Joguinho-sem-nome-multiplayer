var state = {
    screen: {
        width: 0,
        height: 0
    },
    players: [],
    fruits: []
}

var id = null
var socket = null

try {
    socket = new WebSocket("ws://localhost:8080")
}catch(e) {
    console.error(e)
}

socket.onmessage = e => {
    id = JSON.parse(e.data)
}
socket.onopen = e => {
    console.log("Open connection")
}

socket.onerror = e => {
    socket.close()
}

var set1 = setInterval(() => {
    if(socket.readyState) {

        socket.onmessage = e => {
            state = JSON.parse(e.data)
        }

        socket.send(JSON.stringify({action: "state"}))
    }
}, 100)


const SIZE = 30

function setup() {
    setInterval(() => {
        if(screen.width !== 0 && screen.height !== 0) {
            createCanvas(state.screen.width * SIZE, state.screen.height * SIZE)
        }
    })
    background(125)
}

function draw() {
    background(125)
    if(state.players.length > 0) {
        for(const player of state.players) {
            fill(50, 50, 50)
            if(player.id === id) {
                fill(200, 50, 50)
            }
            square(player.x * SIZE, player.y * SIZE, SIZE)
        }
    }   

    if(state.fruits.length > 0) {
        for(const fruit of state.fruits) {
            fill(0, 200, 0)
            square(fruit.x * SIZE, fruit.y * SIZE, SIZE)
        }
    }
}

function keyPressed({key}) {
    socket.send(JSON.stringify({action: "move", id: id, key}))
}

window.onbeforeunload = () => {
    socket.onclose = () => {}
    socket.send(JSON.stringify({action: "close", id: id}))
    socket.close()
}
