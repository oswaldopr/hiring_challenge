# Workana Hiring Challenge

Hi Workana!

In first place, I'm grateful for be taken into account to participate in this challenge. It was exciting somehow. Thanks!

In second place... I like English but I like more Spanish. So, it's very possible that I have some mistakes here and in the code as well. But, I wanted to stick to the language of the challenge.

## Some issues:
- My work environment is on Windows. PHP version is 5.1.2. Right now, I'm working on a project that it's in final stage and keeps me busy. For that reason, I didn't wanted change my work environment. I downloaded Fedora Workstation 23 to install it but I never before have installed Apache or PHP on Linux. Because of that, I desisted for the time it could take me. As I wrote, the project in which work takes me a lot of time. Furthermore, perhaps is not of your interest but it's not a minor detail: I'm from Venezuela and currently we have a critical situation with electricity. Therefore, we have daily power cuts for four hours (it's an insane).
- Redis not works *officially* on Windows. I found this in Redis's site: *"The Redis project does not officially support Windows. However, the Microsoft Open Tech group develops and maintains this Windows port targeting Win64."*. My platform is on 32-bit. Maybe I could have done some more but I didn't.
- I couldn't install Redis, Composer or PHPUnit. Again, maybe I could have done some more but I didn't.
- Because I didn't install Redis I couldn't run `index.php`. However, I read about Redis for learn how it works. Also, I saw the files `create_redis_keys.php`, `redis_commands` and `FriendsList.php` to understand more. At this point, it's obvious that I didn't use Redis before.
- I simulated almost everything that I couldn't get. In this way, I understood the logic of the script `index.php`.
- I haven't used Git either. I use **SubVersion** because is the CVS which has been used in all projects that I worked. But, I read some chapters of the book ***ProGit***, specifically: Chapter 1: Getting Started, Chapter 2: Git Basics, Chapter 3: Git Branching and Chapter 6: GitHub.
- Honestly, I've never made unit testing. So, I investigated about them too. As consequence, I developed an improvised, basic and crafted *tester*. It's pretty adhered to this project. But it's also pretty cool.
- Well, it's enough... It's showtime!

## What you get:
1. Classes for responses and requests.
2. Script `index.php` was upgraded based on the above classes.
3. The code was improved to be *blazing fast*. Of course, it could still be better.
4. Unit testing. I apologize if it's not what you expected.

### Considerations for the tester:
- It's based on files. You must grant write permission to directories `test_files` and `result_files`. These are subdirectories of `tester`.
- The tests must be written in a *test file*. You can generate these files running `testfiles.php`. Executing `testfiles.php` without arguments it will display a brief description and instructions about how it works.
- The results of tests are written in a *result file*. To apply the unit testing you must run `tester.php`. Executing `tester.php` without arguments it will display a brief description and instructions about how it works.
- I leave some files to you see my unit testing.
