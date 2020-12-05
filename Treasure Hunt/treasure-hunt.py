from turtle import *
from base import vector

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
    for index in range(len(grids)):
        grid = grids[index]
        x = (index % 8) * 20 - 200
        y = 180 - (index // 8) * 20
        gridLayout(x,y, grid)
        if grid > 0:
            path.up()
            path.goto(x + 10, y + 10)
            path.dot(3, 'white')
        else:
            path.up()
            path.goto(x + 8, y + 3)
            path.write('#')
    up()
    goto(pacman.x, pacman.y)
    dot(15, 'yellow')
    update()

setup(800, 420, 300, 150)
title("Treasure Hunt - Evermos!")
tracer(False)
index()
done()