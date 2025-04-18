<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "vendor") {
    header("Location: ../auth/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Store Management | Vendor Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="public/css/stylesheet.css">
</head>
<body>
    <div class="container">
        <header class="dashboard-header">
            <h2>Store Management</h2>
            <a href="../auth/logout.php" class="btn btn-danger">Logout</a>
        </header>
        <main class="dashboard-content">
            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5>Store Appearance</h5>
                        </div>
                        <div class="card-body">
                            <form>
                                <div class="mb-3">
                                    <label for="storeName" class="form-label">Store Name</label>
                                    <input type="text" class="form-control" id="storeName" value="My Awesome Store">
                                </div>
                                <div class="mb-3">
                                    <label for="storeDescription" class="form-label">Store Description</label>
                                    <textarea class="form-control" id="storeDescription" rows="3">We offer high-quality products with excellent customer service.</textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="logoUpload" class="form-label">Store Logo</label>
                                    <input class="form-control" type="file" id="logoUpload">
                                    <div class="mt-2">
                                        <img src="https://via.placeholder.com/150" alt="Current logo" class="img-thumbnail" width="150">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="bannerUpload" class="form-label">Store Banner</label>
                                    <input class="form-control" type="file" id="bannerUpload">
                                    <div class="mt-2">
                                        <img src="https://via.placeholder.com/800x200" alt="Current banner" class="img-thumbnail" width="100%">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="primaryColor" class="form-label">Primary Color</label>
                                    <input type="color" class="form-control form-control-color" id="primaryColor" value="#4a6fa5" title="Choose your color">
                                </div>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5>Store Policies</h5>
                        </div>
                        <div class="card-body">
                            <form>
                                <div class="mb-3">
                                    <label for="shippingPolicy" class="form-label">Shipping Policy</label>
                                    <textarea class="form-control" id="shippingPolicy" rows="3">Standard shipping takes 3-5 business days. Express shipping available for an additional fee.</textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="returnPolicy" class="form-label">Return Policy</label>
                                    <textarea class="form-control" id="returnPolicy" rows="3">Items can be returned within 30 days of purchase for a full refund. Items must be in original condition.</textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="refundPolicy" class="form-label">Refund Policy</label>
                                    <textarea class="form-control" id="refundPolicy" rows="3">Refunds will be processed within 3-5 business days after we receive the returned item.</textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="privacyPolicy" class="form-label">Privacy Policy</label>
                                    <textarea class="form-control" id="privacyPolicy" rows="3">We respect your privacy and will never share your personal information with third parties without your consent.</textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Save Policies</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>