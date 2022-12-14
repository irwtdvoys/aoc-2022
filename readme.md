# Advent of Code 2022

https://adventofcode.com/2022

## Notes

##### Day 01

Nice simple day, used OOP for the elves.

##### Day 02

Making use of the enums from php 8.1

##### Day 03

Array intersect dealt with most of the task, then a method to tweak the result of `ord` to match the specified values in the task

##### Day 04

Not much to say here, just a couple of if statements... A bit of regex for the parsing I suppose.

##### Day 05

Parsing the data was the main time sink here, also my standard load method trims so that caused issues as well.

##### Day 06

Unusually simple. I assume it's the basis for future tasks.

##### Day 07

Used a nested data structure with reference to parent object. Once the data was loaded the tasks were really straight forward.

##### Day 08

Getting worried about the difficulty spike this weekend. Nothing really of note today.

##### Day 09

Had to do a small update for part 2 to allow any number of knots.

##### Day 10

Only way I could solve the delay in command execution was to calculate the execution cycles up front and then work through the cycles. This solution meant part 2 was pretty easy.

##### Day 11

Fun task, went to the trouble of upgrading the LCM method from my support library to allow more than two parameters, only to realise they were all prime and I could have gotten away with an `array_product` instead!

##### Day 12

Did a full BFS for the first part and was able to reuse it in reverse for part two. Just searched the results for the shortest `a` location.

##### Day 13

Had some trouble with part 1, misunderstood that checking stops on arrays once a successfull match is found. Spaceship operator came in handy then! Changed the comparison method in part 2 to allow it to be used in a `usort`, part 2 might have looked cleaner if I kept the packets encoded so the search was more efficient.

##### Day 14

Had to resort to a couple of cheeky throws to bail out when the sand reached its end goal but on the hole straight forward.
