use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Hoathis\SymfonyConsoleBridge\Helper as Helper;
use DomDocument;

class MatrixHelper extends HelperBase
{

    const NAME = 'matrix';

    public function getName()
    {
        return self::NAME;
    }

    public function draw(Helper\CursorHelper $cursor, OutputInterface $output, $width, $height)
    {
        $Counter;

        $Interval = 100; // Normal Flowing of Matrix Rain
        $FullFlow = $Interval + 30; // Fast Flowing of Matrix Rain
        $Blacking = $FullFlow + 50; // Displaying the Test Alone

        $NormalColor = 2;
        $GlowColor = 10;
        $FancyColor = 15;
        $TextInput = "Matrix";

        //$AsciiCharacter//Randomised Inputs
/***************/
        $y = [];
        $cursor->clear($output, Helper\CursorHelper::CLEAR_SCREEN);
        for($x = 0;$x < $width;++$x)//Setting the cursor at random at program startup
        {
            $y[x] = rand(1, $height);
        }
        for ($x = 0; $x < $width; ++$x)
        {
            if ($x % 10 == 1)//Randomly setting up the White Position
                $cursor->colorize($output, "fg($FancyColor)");
            else
                $cursor->colorize($output, "fg($GlowColor)");
            $cursor->moveTo($output, $x, $y[$x]);
            $output->write($AsciiCharacter);
            if ($x % 10 == 9)
                $cursor->colorize($output, "fg($FancyColor)");
            else
                $cursor->colorize($output, "fg($NormalColor)");
            $temp = $y[$x] - 2;
            $cursor->moveTo($output, $x, inScreenYPosition($temp, $height));
            $output->write($AsciiCharacter);
            $temp1 = $y[$x] - 20;
            $cursor->moveTo($output, $x, inScreenYPosition($temp1, $height));
            $output->write(' ');
            $y[$x] = inScreenYPosition($y[$x] + 1, $height);
        }
    }
}

function inScreenYPosition($yPosition, $height)
{
    if ($yPosition < 0)//When there is negative value
        return $yPosition + $height;
    else if ($yPosition < $height)//Normal 
        return $yPosition;
    else// When y goes out of screen when autoincremented by 1
        return 0;

}


using System;

namespace matrix
{
    class Program
    {
        static int Counter;
        static Random rand = new Random();

        static int Interval = 100; // Normal Flowing of Matrix Rain
        static int FullFlow = Interval + 30; // Fast Flowing of Matrix Rain
        static int Blacking = FullFlow + 50; // Displaying the Test Alone

        static ConsoleColor NormalColor = ConsoleColor.DarkGreen;
        static ConsoleColor GlowColor = ConsoleColor.Green;
        static ConsoleColor FancyColor = ConsoleColor.White;
        static String TextInput = "Matrix";

        static char AsciiCharacter//Randomised Inputs
        {
            get
            {
                int t = rand.Next(10);
                if (t <= 2)
                    return (char)('0' + rand.Next(10));
                else if (t <= 4)
                    return (char)('a' + rand.Next(27));
                else if (t <= 6)
                    return (char)('A' + rand.Next(27));
                else
                    return (char)(rand.Next(32, 255));
            }
        }
        static void Main()
        {

            Console.ForegroundColor = NormalColor;
            Console.WindowLeft = Console.WindowTop = 0;
            Console.WindowHeight = Console.BufferHeight = Console.LargestWindowHeight;
            Console.WindowWidth = Console.BufferWidth = Console.LargestWindowWidth;
            Console.SetWindowPosition(0, 0);
            Console.CursorVisible = false;

            int width, height;
            int[] y;
            Initialize(out width, out height, out y);//Setting the Starting Point
           
            while (true)
            {
                Counter = Counter + 1;
                UpdateAllColumns(width, height, y);
                if (Counter > (3 * Interval))
                    Counter = 0;

            }
        }
        private static void UpdateAllColumns(int width, int height, int[] y)
        {
            int x;
            if (Counter < Interval)
            {
                for (x = 0; x < width; ++x)
                {
                    if (x % 10 == 1)//Randomly setting up the White Position
                        Console.ForegroundColor = FancyColor;
                    else
                        Console.ForegroundColor = GlowColor;
                    Console.SetCursorPosition(x, y[x]);
                    Console.Write(AsciiCharacter);

                    if (x % 10 == 9)
                        Console.ForegroundColor = FancyColor;
                    else
                        Console.ForegroundColor = NormalColor;
                    int temp = y[x] - 2;
                    Console.SetCursorPosition(x, inScreenYPosition(temp, height));
                    Console.Write(AsciiCharacter);

                    int temp1 = y[x] - 20;
                    Console.SetCursorPosition(x, inScreenYPosition(temp1, height));
                    Console.Write(' ');
                    y[x] = inScreenYPosition(y[x] + 1, height);
                   
                }
            }
            else if (Counter > Interval && Counter < FullFlow)
            {
                for (x = 0; x < width; ++x)
                {

                    Console.SetCursorPosition(x, y[x]);
                    if (x % 10 == 9)
                        Console.ForegroundColor = FancyColor;
                    else
                        Console.ForegroundColor = NormalColor;

                    Console.Write(AsciiCharacter);//Printing the Character Always at Fixed position

                    y[x] = inScreenYPosition(y[x] + 1, height);
                }
            }
            else if (Counter > FullFlow)
            {
                for (x = 0; x < width; ++x)
                {
                    Console.SetCursorPosition(x, y[x]);
                    Console.Write(' ');//Slowly blacking out the Screen
                    int temp1 = y[x] - 20;
                    Console.SetCursorPosition(x, inScreenYPosition(temp1, height));
                    Console.Write(' ');
                    if (Counter > FullFlow && Counter < Blacking)// Clearing the Entire screen to get the Darkness
                    {
                        if (x % 10 == 9)
                            Console.ForegroundColor = FancyColor;
                        else
                            Console.ForegroundColor = NormalColor;
                        int temp = y[x] - 2;
                        Console.SetCursorPosition(x, inScreenYPosition(temp, height));
                        Console.Write(AsciiCharacter);//The Text is printed Always

                    }
                    Console.SetCursorPosition(width / 2, height / 2);
                    Console.Write(TextInput);
                    y[x] = inScreenYPosition(y[x] + 1, height);
                }

            }
        }
        public static int inScreenYPosition(int yPosition, int height)
        {
            if (yPosition < 0)//When there is negative value
                return yPosition + height;
            else if (yPosition < height)//Normal 
                return yPosition;
            else// When y goes out of screen when autoincremented by 1
                return 0;

        }
        private static void Initialize(out int width, out int height, out int[] y)
        {
            height = Console.WindowHeight;
            width = Console.WindowWidth - 1;
            y = new int[width];
            Console.Clear();

            for (int x = 0; x < width; ++x)//Setting the cursor at random at program startup
            {
                y[x] = rand.Next(height);
            }
        }
    }
}
