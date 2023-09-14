using System;
using System.Collections.Generic;
//Creamos una funcion
namespace BlackjackGame
{
    class Program
    {
        static void Main(string[] args)
        {
            Console.WriteLine("¡Bienvenido a Blackjack!");

            while (true) //Este while es para que pregunte si quiere jugar otra vez
            {
                PlayGame();
                Console.Write("¿Quieres jugar otra vez? (s/n): ");
                if (Console.ReadLine().ToLower() != "s")
                    break;
            }

            Console.WriteLine("¡Gracias por jugar!");
        }

        static void PlayGame() //funcion principal
        {
            // Crear una baraja y barajarla
            //El metodo generate y se guarda en deck que es una lista
            List<string> deck = GenerateDeck();
            ShuffleDeck(deck);

            // Cartas del jugador y la casa
            List<string> playerHand = new List<string>();
            List<string> dealerHand = new List<string>();

            // Repartir las dos primeras cartas a cada jugador
            playerHand.Add(deck[0]);
            playerHand.Add(deck[1]);
            dealerHand.Add(deck[2]);
            dealerHand.Add(deck[3]);
            //Muestro cada una de als cartsa que sale es decir las 2 primeras las uno
            //meidante JOIN
            Console.WriteLine("Tu mano: " + string.Join(", ", playerHand));
            //so se muestra la primer carta
            Console.WriteLine("Carta visible de la casa: " + dealerHand[0]);

            // Jugar el turno del jugador
            while (CalculateHandValue(playerHand) < 21)
            {
                Console.Write("¿Quieres pedir otra carta? (s/n): ");
                if (Console.ReadLine().ToLower() == "s")
                {
                    playerHand.Add(deck[playerHand.Count]);
                    //Muestroa otra carta si esocgio s
                    Console.WriteLine("Tu mano: " + string.Join(", ", playerHand));
                }
                else
                {
                    break;
                }
            }

            // Jugar el turno de la casa
            while (CalculateHandValue(dealerHand) < 17)
            {
                dealerHand.Add(deck[dealerHand.Count]);
            }

            Console.WriteLine("Mano de la casa: " + string.Join(", ", dealerHand));

            // Determinar el resultado
            int playerScore = CalculateHandValue(playerHand);
            int dealerScore = CalculateHandValue(dealerHand);

            if (playerScore > 21)
            {
                Console.WriteLine("Has perdido.");
            }
            else if (dealerScore > 21 || playerScore > dealerScore)
            {
                Console.WriteLine("¡Ganaste!");
            }
            else if (playerScore == dealerScore)
            {
                Console.WriteLine("Empate.");
            }
            else
            {
                Console.WriteLine("La casa gana.");
            }
        }

        static List<string> GenerateDeck()
        {
            //Las istas a usar y una lista vacia para guardar los valores random
            List<string> suits = new List<string> { "Corazones", "Diamantes", "Picas", "Tréboles" };
            List<string> ranks = new List<string> { "2", "3", "4", "5", "6", "7", "8", "9", "10", "J", "Q", "K", "A" };
            List<string> deck = new List<string>();
            //RANKS NUMEROS Y SUITS NOMBRE con cada foreach busco los valores y los uno
            foreach (var suit in suits)
            {
                foreach (var rank in ranks)
                {
                    deck.Add(rank + " de " + suit);
                }
            }

            return deck;
        }

        static void ShuffleDeck(List<string> deck)
        {
            Random rng = new Random();
            int n = deck.Count;
            while (n > 1)
            {
                n--;
                int k = rng.Next(n + 1);
                string value = deck[k];
                deck[k] = deck[n];
                deck[n] = value;
            }
        }
//funcion prubea quiza funcione o no: CON IF ELSE se asignan casos especiales es decirs los aces
        static int CalculateHandValue(List<string> hand)
        {
            int value = 0;
            int numAces = 0;

            foreach (var card in hand)
            {
                string rank = card.Split(' ')[0];
                if (rank == "A")
                {
                    numAces++;
                    value += 11;
                }
                else if (rank == "K" || rank == "Q" || rank == "J")
                {
                    value += 10;
                }
                else
                {
                    value += int.Parse(rank);
                }
            }
//Para que no se pase 
            while (value > 21 && numAces > 0)
            {
                value -= 10;
                numAces--;
            }

            return value;
        }
    }
}
