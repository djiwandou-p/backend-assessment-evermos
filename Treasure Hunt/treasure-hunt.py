from turtle import *
from base import vector
import random
from math import sqrt

path = Turtle(visible = False)
writer = Turtle(visible = False)
aim = vector(20, 0)
pacman = vector(-170, 110)
grids = [
    0, 0, 0, 0, 0, 0, 0, 0,
    0, 1, 1, 1, 1, 1, 1, 0,
    0, 1, 0, 0, 0, 1, 1, 0,
    0, 1, 1, 1, 0, 1, 0, 0,
    0, 1, 0, 1, 1, 1, 1, 0,
    0, 0, 0, 0, 0, 0, 0, 0
]
obstacleBlock = []
treasureLocate = []
stepAmount = 0
distance = 0
isTreasureFound = False

def gridLayout(x, y, val):
    path.up()
    path.goto(x,y)
    path.down()
    path.begin_fill()
    if val == 1:
        path.color('black', 'blue')
    elif val == 0:
        path.color('black', 'red')
    for count in range(4):
        path.forward(20)
        path.left(90)
    path.end_fill()

def index():
    global obstacleBlock, treasureLocate
    clearPaths = [];
    for index in range(len(grids)):
        grid = grids[index]
        x = (index % 8) * 20 - 200
        y = 180 - (index // 8) * 20
        gridLayout(x,y, grid)
        if grid > 0:
            clearPaths.append({'x':x+10, 'y':y+10 })
            path.up()
            path.goto(x + 10, y + 10)
            path.dot(3, 'white')
        else:
            obstacleBlock.append({'x':x+10, 'y':y+10 })
            path.up()
            path.goto(x + 8, y + 3)
            path.write('#')
    treasureLocate = random.choice(clearPaths)
    path.up()
    path.goto(treasureLocate['x']-2, treasureLocate['y']-7)
    path.write('$')

    up()
    goto(pacman.x, pacman.y)
    dot(15, 'yellow')
    update()

def moveTo(x, y):
    global isTreasureFound
    if isTreasureFound == False:
        if validBlock(pacman + vector(x,y)):
            aim.x = x
            aim.y = y
            move()

def move():
    clear()
    pacman.move(aim)
    up()
    goto(pacman.x, pacman.y)
    dot(15, 'yellow')
    update()
    treasureHunt(pacman.x, pacman.y)

def validBlock(point):
    global obstacleBlock
    isObstacle = list(filter(lambda item: item['x'] == point.x and item['y'] == point.y, obstacleBlock))
    if len(isObstacle) == 0:
        return True
    else:
        return False

def treasureHunt(x, y):
    global treasureLocate, stepAmount, distance, isTreasureFound;
    myLocation = (x, y)
    x1 = treasureLocate['x']
    y1 = treasureLocate['y']
    treasureLocation = (x1, y1)
    if (myLocation != treasureLocation):
        distance = sqrt((x-x1)*(x-x1)+(y-y1)*(y-y1))
        stepAmount += 1
        writer.up()
        writer.setposition(50, 190-(stepAmount*15))
        message = "Step "+str(stepAmount)+". The distance you are from the treasure is: "+str(round(distance,2))
        writer.write(message)
    else:
        isTreasureFound = True
        writer.up()
        writer.setposition(50, 190-(stepAmount*15)-15)
        message = "Finish. It only took you "+str(stepAmount)+" steps!";
        writer.write(message)

setup(800, 420, 300, 150)
title("Treasure Hunt - Evermos!")
tracer(False)
writer.up()
writer.setposition(50, 190)
writer.write('Start')

listen()
onkey(lambda: moveTo(20,0), 'Right')
onkey(lambda: moveTo(-20,0), 'Left')
onkey(lambda: moveTo(0,20), 'Up')
onkey(lambda: moveTo(0,-20), 'Down')

index()
done()