
using System;
using System.Collections.Generic;
using System.Linq;
using System.Net.Http.Headers;
using System.Net.Http;
using System.Text;
using System.Threading.Tasks;
// Welcome to AssemblyAI! Get started with the API by transcribing
// a file using C#.
//
// In this example, we'll transcribe a local file. Get started by
// downloading the snippet, then update the 'filename' variable
// to point to the local path of the file you want to upload and
// use the API to transcribe.
//
// IMPORTANT: Update line 130 to point to a local file.
//
// Have fun!


using System.IO;

using System.Net.Http.Json;

using System.Text.Json;


namespace ConsoleApp1
{
    internal class SpeechToText
    {
        private static readonly HttpClient HttpClient = new HttpClient();

        public static async Task Main(string [] args)
        {
            // Your API token is already set in this variable
            var apiToken = "4a920130b6224e369e93fd1ef57c7794";
            //Environment.GetEnvironmentVariable("ASSEMBLY_AI_API_KEY"); // only pre-modification for git
            HttpClient.DefaultRequestHeaders.Authorization = new AuthenticationHeaderValue(apiToken);

            // -----------------------------------------------------------------------------
            // Update the file path here, pointing to a local audio or video file.
            // If you don't have one, download a sample file: https://storage.googleapis.com/aai-web-samples/espn-bears.m4a
            // You may also remove the upload step and update the 'audio_url' parameter in the
            // 'GetTranscriptAsync' function to point to a remote audio or video file.
            // -----------------------------------------------------------------------------
            
            var path = "C:\\Users\\Ng\\projects\\Actual Nozzle\\Nozzle\\Nozzle\\bin\\x86\\Release\\recording.mp3";
            path = args[0];
            var uploadedFileUrl = await UploadFileAsync(path);
            var transcript = await GetTranscriptAsync(uploadedFileUrl);
            Console.WriteLine("Transcript:" + transcript);
        }

        private static async Task<string> UploadFileAsync(string path)
        {
            using (var fileStream = File.OpenRead(path))
            {
                using (var fileContent = new StreamContent(fileStream))
                {
                    fileContent.Headers.ContentType = new MediaTypeHeaderValue("application/octet-stream");

                    using (var response = await HttpClient.PostAsync("https://api.assemblyai.com/v2/upload", fileContent))
                    {
                        if (!response.IsSuccessStatusCode)
                            throw new Exception($"Error: {response.StatusCode} - {response.ReasonPhrase}");

                        var jsonDoc = await response.Content.ReadFromJsonAsync<JsonDocument>();
                        return jsonDoc.RootElement.GetProperty("upload_url").GetString();
                    }
                }
            }
        }


        private static async Task<string> GetTranscriptAsync(string audioUrl)
        {
            var data = new { audio_url = audioUrl };
            var json = System.Text.Json.JsonSerializer.Serialize(data);
            var content = new StringContent(json, Encoding.UTF8, "application/json");

            using (var response = await HttpClient.PostAsync("https://api.assemblyai.com/v2/transcript", content))
            {
                var responseJson = await response.Content.ReadFromJsonAsync<JsonDocument>();

                var transcriptId = responseJson.RootElement.GetProperty("id").GetString();
                var pollingEndpoint = $"https://api.assemblyai.com/v2/transcript/{transcriptId}";

                while (true)
                {
                    using (var pollingResponse = await HttpClient.GetAsync(pollingEndpoint))
                    {
                        var pollingJsonDocument = await pollingResponse.Content.ReadFromJsonAsync<JsonDocument>();
                        var pollingJsonObject = pollingJsonDocument.RootElement;

                        var status = pollingJsonObject.GetProperty("status").GetString();
                        switch (status)
                        {
                            case "processing":
                                await Task.Delay(TimeSpan.FromSeconds(3));
                                break;
                            case "completed":
                                return pollingJsonObject.GetProperty("text").GetString();
                            case "error":
                                var error = pollingJsonObject.GetProperty("error").GetString();
                                throw new Exception($"Transcription failed: {error}");
                            default:
                                throw new Exception("This code should not be reachable.");
                        }
                    }
                }
            }
        }
    }
}
