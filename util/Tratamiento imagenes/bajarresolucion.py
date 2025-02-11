from PIL import Image
import os

def downscale_images(folder_path, output_folder, target_width=400):
    """
    Downscale all images in the specified folder so that their width is 400 pixels,
    maintaining the aspect ratio, and save the downscaled images to an output folder.

    :param folder_path: Path to the folder containing the images.
    :param output_folder: Path to the folder where the downscaled images will be saved.
    :param target_width: The target width for the images (default is 400 pixels).
    """
    if not os.path.exists(output_folder):
        os.makedirs(output_folder)

    for filename in os.listdir(folder_path):
        file_path = os.path.join(folder_path, filename)

        try:
            # Open the image file
            with Image.open(file_path) as img:
                # Calculate the new height to maintain aspect ratio
                aspect_ratio = img.height / img.width
                target_height = int(target_width * aspect_ratio)

                # Resize the image
                img_resized = img.resize((target_width, target_height))

                # Save the resized image to the output folder
                output_path = os.path.join(output_folder, filename)
                img_resized.save(output_path)

                print(f"Resized and saved: {output_path}")

        except Exception as e:
            print(f"Error processing file {filename}: {e}")

# Example usage
input_folder = "../../static/"
output_folder = "../../static/"
downscale_images(input_folder, output_folder)
